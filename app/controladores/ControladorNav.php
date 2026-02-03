<?php
// includes/controller.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Argentina/Buenos_Aires');

define('ROOT', __DIR__ . '/../');

require_once ROOT . 'includes/functions.php';
require_once ROOT . 'modelos/permisos.php';
require_once ROOT . 'includes/header.php';
?>
