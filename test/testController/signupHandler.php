<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Avvio la sessione (necessaria per gestire login e dati utente)
//session_start();

// Includi il file autoload se esiste, oppure manualmente i file necessari
require_once __DIR__ . '/../../vendor/autoload.php';

use Controller\CReview;
use Utility\USessions;
use Controller\CUser;
use Utility\UCookies;
use Utility\UHTTPMethods;
use Utility\UServer;
use Entity\EUser;
use Foundation\FUser;
use Foundation\FPersistentManager;



// Creo una nuova istanza del controller
$controller = new CUser();
$session=USessions::getIstance();
$session->setValue('idUser', 1);

$res=$controller->isLogged();
if($res===false) {
    echo "Utente non loggato";
} else {
    echo "Utente loggato";
}
print_r($_SESSION);




