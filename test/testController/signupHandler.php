<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Avvio la sessione (necessaria per gestire login e dati utente)
//session_start();

// Includi il file autoload se esiste, oppure manualmente i file necessari
require_once __DIR__ . '/../../vendor/autoload.php';

use Controller\CCreditCard;
use Controller\CReservation;
use Controller\CReview;
use Utility\USessions;
use Controller\CUser;
use Utility\UCookies;
use Utility\UHTTPMethods;
use Utility\UServer;
use Entity\EUser;
use Foundation\FUser;
use Foundation\FPersistentManager;
use Entity\ECreditCard;
use Foundation\FCreditCard;
use Entity\EReview;


//$r1=new EReview(1, null, 5, "Il nostro software è una bomba", new DateTime(), null);
// Creo una nuova istanza del controller
$controller = new CUser();
//$controller2 = new CCreditCard();
$controller3 = new CReview();
$controller4 = new CReservation();

//$controller->showProfile();
//$controller->checkLogin();
//$controller2->checkAddCreditCard();
//$controller3->checkAddReview();
//$controller3->deleteReview(13);
//$controller3->showReviewsPage();


//$controller4->showTableForm();
//$controller4->showValidTable();
//print_r($_SESSION);
//$controller4->dataTableReservation();
//$controller4->checkTableReservation();

//$controller4->showRoomForm();
//$controller4->showValidRooms();
$controller4->dataRoomReservation();









