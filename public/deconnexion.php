<?php 
session_start();

// Suppression des variables de session et de la session
$_SESSION = array();
session_destroy();
header( 'Location: index.html' ) ;
// Suppression des cookies de connexion automatique
