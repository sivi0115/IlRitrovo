<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Avvio la sessione (necessaria per gestire login e dati utente)
session_start();

// Includi il file autoload se esiste, oppure manualmente i file necessari
require_once __DIR__ . '/../../vendor/autoload.php';
use Controller\CReservation;
use Controller\CReview;
use Controller\CUser;
use Utility\UCookies;
use Utility\UHTTPMethods;
use Utility\USessions;
use Utility\UServer;
use Entity\EUser;
use Foundation\FUser;
use Foundation\FPersistentManager;


// Creo una nuova istanza del controller
$controller = new CUser();

// Chiamo il metodo signup()
// Questo metodo si occuperà di validare i dati e fare l'inserimento a DB
$controller->signup();
