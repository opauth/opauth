<?php
define('LIB', '../lib/Opauth/');

require LIB.'opauth.php';
$Opauth = new Opauth($_SERVER['REQUEST_URI']);