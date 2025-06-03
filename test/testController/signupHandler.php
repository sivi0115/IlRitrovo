<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Avvio la sessione (necessaria per gestire login e dati utente)
session_start();

// Includi il file autoload se esiste, oppure manualmente i file necessari
require_once __DIR__ . '/../../vendor/autoload.php';
use Utility\USessions;
use Controller\CUser;
use Utility\UCookies;
use Utility\UHTTPMethods;
use Utility\UServer;
use Entity\EUser;
use Foundation\FUser;
use Foundation\FPersistentManager;


//Simula un utente loggato
USessions::setSessionElement('idUser', 1);
// Creo una nuova istanza del controller
$controller = new CUser();


// Questo metodo si occuperà di validare i dati e fare l'inserimento a DB
//$controller->signup();
//Questo si occupa di fare il login di un utente
//$controller->login();
//Verifica se funziona isLogged()
//$controller->isLogged();
//Verifica il funzionamento di editProfileData()
$controller->editProfileData();
//Verifica il funzionamento di editUsername()
//$controller->editProfileMetadata();
