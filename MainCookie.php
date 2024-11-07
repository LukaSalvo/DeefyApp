<?php
require_once 'vendor/autoload.php';
use \iutnc\deefy\audio\tracks as tracks;
use \iutnc\deefy\render as R;
use \iutnc\deefy\audio\lists as L;
use \iutnc\deefy\exception as E;




if(isset($_COOKIE["track"])){
    $track = unserialize($_COOKIE["track"]);
    $rTrack = new R\AlbumTrackRenderer($track);
    echo $rTrack->render(2);

} else{
    $t1 = new tracks\AlbumTrack("Thriller","music/01-Im_with_you_BB-King-Lucille.mp3");
    $serializableAblumTrack = serialize($t1);
    setcookie("track",$serializableAblumTrack,time() + 60*60*2);
    echo "Cookie créé : track " ;
}
