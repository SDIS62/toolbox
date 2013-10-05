<?php
$loader = require realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";
Zend_Loader_Autoloader::getInstance();