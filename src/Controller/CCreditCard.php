<?php

namespace Controller;

use Utility\UCookies;
use Utility\UHTTPMethods;
use Utility\UServer;
use Utility\USessions;
use DateTime;
use Entity\ECreditCard;
use Entity\EUser;
use Entity\Role;
use Foundation\FUser;
use View\VUser;
use Foundation\FPersistentManager;
use Exception;
use Foundation\FCreditCard;
use Foundation\FPayment;
use View\VCreditCard;

/**
 * Classe Controller CCreditCard
 * Gestisce tutte le operazioni legate a una carta di credito
 */
class CCreditCard {
    /**
     * Costruttore
     */
    public function __construct() {
    }

    /**
     * Function to show form for adding a new Credit Card
     */
    public function showAddCreditCardUserProfile() {
        $view=new VCreditCard();
        $view->showAddCreditCardUserProfile();
    }

    /**
     * Function to show form for adding a new Credit Card from reservation (step3)
     */
    public function showAddCreditCardStep3() {
        $view=new VCreditCard();
        $view->showAddCreditCardStep3();
    }

    /**
     * Function to add a credit card
     */
    public function checkAddCreditCard() {
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        //Prendo i valori dalla richiesta POST HTTP
        $type=UHTTPMethods::post('cardType');
        $number=UHTTPMethods::post('cardNumber');
        $cvv = UHTTPMethods::post('cardCVV');
        $holder=UHTTPMethods::post('cardHolder');
        $expiration=new DateTime(UHTTPMethods::post('expiryDate'));
        //Istanzio un nuovo oggetto ECreditCard e lo aggiungo a db
        $newCreditCard=new ECreditCard(
            null,
            $holder,
            $number,
            $cvv,
            $expiration,
            $type,
            $idUser
        );
        //Aggiungo la carta su db
        if($addedCreditCard=FPersistentManager::getInstance()->create($newCreditCard)!== null) {
            header("Location: /IlRitrovo/public/User/showProfile");
        }  
    }

    /**
     * Function to add a credit card from step 3
     */
    public function checkAddCreditCardStep3() {
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        //Prendo i valori dalla richiesta POST HTTP
        $type=UHTTPMethods::post('cardType');
        $number=UHTTPMethods::post('cardNumber');
        $cvv = UHTTPMethods::post('cardCVV');
        $holder=UHTTPMethods::post('cardHolder');
        $expiration=new DateTime(UHTTPMethods::post('expiryDate'));
        //Istanzio un nuovo oggetto ECreditCard e lo aggiungo a db
        $newCreditCard=new ECreditCard(
            null,
            $holder,
            $number,
            $cvv,
            $expiration,
            $type,
            $idUser
        );
        //Aggiungo la carta su db
        if($addedCreditCard=FPersistentManager::getInstance()->create($newCreditCard)!== null) {
            header("Location: /IlRitrovo/public/Reservation/dataRoomReservation");
        }  
    }

    /**
     * Function for delete an existing credit card
     * 
     * @param $idCreditCard, recived by the HTTP POST request sended by user when press "delete"
     */
    public function deleteCreditcard($idCreditCard) {
        if(FPersistentManager::getInstance()->delete($idCreditCard, FCreditCard::class)) {
            header("Location: /IlRitrovo/public/User/showProfile");
        }
    }

























































}