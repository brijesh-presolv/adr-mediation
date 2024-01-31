<?php

$userIP = $_SERVER['REMOTE_ADDR'];


    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    echo file_get_contents('http://presolv360.test/build/index.html');

?>