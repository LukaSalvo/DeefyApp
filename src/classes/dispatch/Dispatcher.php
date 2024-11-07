<?php

namespace iutnc\deefy\dispatch;

use Exception;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\Logout;
use iutnc\deefy\action\RegisterAction;
use iutnc\deefy\action\SigninAction;


class Dispatcher {


    private string $action;


    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
    }


    /**
     * @throws \Exception
     */
    public function run(): void {

        switch ($this->action) {
            case 'logout':
                $action = new Logout();
                break;
            case 'signinAction':
                $action = new SigninAction();
                break;
            case 'registerAction':
                $action = new RegisterAction();
                break;
            case 'playlist':
                $action = new DisplayPlaylistAction();
                break;
            case 'add-playlist':
                $action = new AddPlaylistAction();
                break;
            case 'add-track':
                $action = new AddPodcastTrackAction();
                break;
            case 'default':
            default:
                $action = new DefaultAction();
                break;
        }

        $res = $action->execute();
        $this->renderPage($res);
    }



    public function renderPage(string $res): void
    {
        $user = null;

        if (isset($_SESSION['user'])) {
            try {
                $user = unserialize($_SESSION['user']);
            } catch (Exception $e) {}
        }

        $output = '
            <html>
            <head>
                <title>Deefy App</title>
                <link rel="stylesheet" href="src/style/style.css"> 
            </head>
            <body>
                <nav>
                    <a href="?action=default">Accueil</a>';

        if ($user !== null) {
            $output .= '<a href =?action=logout>Se Deconnecter</a>
                           <a href =?action=playlist>Mon espace</a>';
        } else {
            $output .= '
             <a href = "?action=signinAction">Connexion</a>
             <a href="?action=registerAction">Inscription</a>
          
            ';
        }
        $output .= '
                </nav>
                <main>'.$res.'</main>
            </body>
            </html>';

        echo $output;
    }
}