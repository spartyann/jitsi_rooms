<?php

function dd(){
    foreach(func_get_args() as $arg) var_dump($arg); 
    exit();
}

function error($error = '') {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Erreur interne du serveur";

    if (DEBUG) echo ':<br />' . $error;
    exit();
}

function apiError($msg = '', $httpCode = 521) {
    header("HTTP/1.1 $httpCode Internal Server Error");
    echo $msg;

    exit();
}

require_once(dirname(__DIR__) . '/config.php');
require_once('db.php');
require_once('db_shemas.php');
require_once('lang.php');
require_once('data.php');

DB::initDB();
DB::clean();
