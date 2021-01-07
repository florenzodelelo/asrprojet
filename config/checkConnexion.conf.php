<?php

require_once 'config/bdd.conf.php';
//print_r2($_COOKIE);
if(isset($_COOKIE['sid'])){
    $sid = $_COOKIE['sid'];
    $utilisateursManager = new utilisateursManager($bdd);
    $utilisateursConnect = $utilisateursManager->getBySid($sid);
    if($utilisateursConnect->getEmail() != ''){
        $utilisateursConnect->isConnect = true;
    } else {
        $utilisateursConnect->isConnect = false;
    }
    //print_r2($utilisateursConnect);
} else {
    $utilisateursConnect = new utilisateurs();
    $utilisateursConnect->isConnect = false;
    //print_r2($utilisateursConnect);
}