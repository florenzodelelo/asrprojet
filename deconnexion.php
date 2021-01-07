<?php
require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';

setcookie('sid', '', -1); //Création du Cookies

// Création de la notification :
$_SESSION['notification']['result'] = 'danger';
$_SESSION['notification']['message'] = 'Vous êtes déconnecté.';
//Redirection vers la page d'acceuil (où la notification va s'afficher)
header('Location: index.php');
exit();