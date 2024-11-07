<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "vendor/autoload.php";
require_once 'src/classes/dispatch/Dispatcher.php';



use \iutnc\deefy\dispatch\Dispatcher;

use iutnc\deefy\repository\DeefyRepository;


session_start();

DeefyRepository::setConfig('src/config/db.config.ini');





$dispatcher = new Dispatcher();
$dispatcher->run();
