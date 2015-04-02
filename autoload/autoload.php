<?php

	function __autoload($class_name) {
        require_once "$class_name.php";
    }

    $CONFIG = require_once 'config.php';
    $PDO = require_once 'database.php';

?>
