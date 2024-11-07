<?php
namespace iutnc\deefy\repository;

use iutnc\deefy\audio\list\Playlist;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\users\User;
use PDO;

class DeefyRepository {

    private PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf) {
        $this->pdo = new PDO($conf['dsn'], $conf['user'], $conf['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public static function getInstance(): ?DeefyRepository {
        if (is_null(self::$instance)) {
            if (empty(self::$config)) {
                throw new \Exception("La configuration de la base de données n'a pas été définie.");
            }
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    public function getUserByEmail(string $email): array
    {
        $query = "SELECT * FROM User WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $this->returnWithoutPassword($stmt->fetch(PDO::FETCH_ASSOC));

    }

    public static function setConfig(string $file) {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Erreur pendant la lecture du fichier de configuration.");
        }
        if (!isset($conf['host'], $conf['dbname'], $conf['username'])) {
            throw new \Exception("Le fichier de configuration ne contient pas toutes les clés nécessaires.");
        }

        self::$config = [
            'dsn' => "mysql:host=" . $conf['host'] . ";dbname=" . $conf['dbname'] ,
            'user' => $conf['username'],
            'pass' => $conf['password']
        ];
    }

    public function saveEmptyPlaylist(Playlist $pk): Playlist {
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $pk->nom]);
        $pk->setID($this->pdo->lastInsertId());
        return $pk;
    }

    public function findAllPlaylists(): array {

        $query = "SELECT * FROM playlist";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $playlists = [];
        while($data = $stmt->fetch()) {
            $playlist = new Playlist($data['nom']);
            $playlist->setId($data['id']);
            array_push($playlists, $playlist);

        }
        return $playlists;
    }


    public function savePodcastTrack(AudioTrack $track): AudioTrack {
        $stmt = $this->pdo->prepare("INSERT INTO track (titre, filename, duree) VALUES (:title, :file, :duration)");
        $stmt->execute([
            'title' => $track->titre,
            'file' => $track->nom_fichier,
            'duration' => $track->duree,
        ]);
        $track->setId($this->pdo->lastInsertId());
        return $track;
    }


    public function addTrackToPlaylist(int $trackId, int $playlistId):bool{

        $queryNb = "SELECT * FROM playlist2track WHERE id_pl = :playlistId ";
        $stmtNb = $this->pdo->prepare($queryNb);
        $stmtNb->execute(["playlistId" => $playlistId]);
        $noTrack = $stmtNb->rowCount() + 1;
        $query = "INSERT INTO playlist2track(id_pl, id_track, no_piste_dans_liste) VALUES(:id_pl, :id_track, :no_piste_dans_liste)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['id_pl' => $playlistId, 'id_track' => $trackId, 'no_piste_dans_liste' => $noTrack]);

    }






    public function loginUser(string $email, string $password): bool
    {
        $query = "SELECT passwd FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && isset($user['passwd'])) {
            return password_verify($password, $user['passwd']);
        }
        return false;

    }

    public function registerUser(string $email, string $hashed_password, int $role): bool
    {
        $query = "INSERT INTO user (email, passwd, role) VALUES (:email, :password, :role)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }


    public function findPlaylistsByUserId(int $userId): array {
        $stmt = $this->pdo->prepare("SELECT p.* FROM playlist p
                                     JOIN user2playlist up ON p.id = up.id_pl
                                     WHERE up.id_user = :userId");
        $stmt->execute(['userId' => $userId]);
        $playlists = [];
        while ($data = $stmt->fetch()) {
            $playlist = new Playlist($data['nom']);
            $playlist->setId($data['id']);
            $playlists[] = $playlist;
        }
        return $playlists;
    }
    
    public function findTracksByPlaylistId(int $playlistId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM track 
                                     INNER JOIN playlist2track pt ON track.id = pt.id_track
                         
                                     WHERE pt.id_pl = :playlistId");
        $stmt->execute(['playlistId' => $playlistId]);
        $tracks = [];
        while ($data = $stmt->fetch()) {
            $track = new AudioTrack($data['titre'], $data['filename']);
            $track->setId($data['id']);
            $track->__set('genre', $data['genre']);
            $track->__set('duree', $data['duree']);
            $track->__set('auteur', $data['artiste_album']);
            $tracks[] = $track;
        }
        return $tracks;
    }

    public function saveEmptyPlaylistForUser(string $playlistName, int $userId): int {
        try {
            $this->pdo->beginTransaction();
            

            $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
            $stmt->execute(['nom' => $playlistName]);
            $playlistId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("INSERT INTO user2playlist (id_user, id_pl) VALUES (:userId, :playlistId)");
            $stmt->execute(['userId' => $userId, 'playlistId' => $playlistId]);
    
            $this->pdo->commit();
            return $playlistId;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw new \Exception("Erreur lors de la création de la playlist et de l'association avec l'utilisateur : " . $e->getMessage());
        }
    }

    public function findAllTracks(): array {
        $stmt = $this->pdo->query("SELECT * FROM track");
        $tracks = [];
        while ($data = $stmt->fetch()) {
            $track = new AudioTrack($data['titre'], $data['filename'] );
            $track->__set('genre', $data['genre']);
            $track->__set('duree', $data['duree']);
            $track->setId($data['id']);
            $tracks[] = $track;
        }
        return $tracks;
    }

    private function returnWithoutPassword(array $user): array
    {
        if ($user) {
            unset($user['passwd']);
        }
        return $user;
    }


    public function getPlaylistById(int $playlistId): ?Playlist {
        $stmt = $this->pdo->prepare("SELECT * FROM playlist WHERE id = :id");
        $stmt->execute(['id' => $playlistId]);
        $data = $stmt->fetch();
        if ($data) {
            $playlist = new Playlist($data['nom']);
            $playlist->setId($data['id']);
            return $playlist;
        }
        return null;
    }
}
