<?php

function dd(){
    foreach(func_get_args() as $arg) var_dump($arg); 
    exit();
}

function error($error = '') {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Erreur interne du serveur";

    dd($error);
    if (DEBUG) echo ':<br />' . $error;

    exit();
}

require_once(dirname(__DIR__) . '/config.php');
require_once('db.php');
require_once('db_shemas.php');
require_once('lang.php');

DB::initDB();

