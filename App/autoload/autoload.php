<?php

	function __autoload($class_name) {
        require_once "App/$class_name.php";
    }

    $CONFIG = require_once 'autoload/config.php';
    $PDO = require_once 'autoload/database.php';

?>
