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
    public function showAddCreditCard() {
        //Da implementare
    }

    /**
     * Function to add a credit card
     */
    public function checkAddCreditCard() {
        $view=new VUser();
        $session=USessions::getIstance();
        $session->startSession();
        //Carico l'id dell'utente dalla sessione
        $idUser=$session->readValue('idUser');
        //Carico l'utente dal db
        $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
        //Istanzio un nuovo oggetto ECreditCard con i dati provenienti dallo script HTML (POST)
        $newCreditCard=new ECreditCard(
            null,
            UHTTPMethods::post('holder'),
            UHTTPMethods::post('number'),
            UHTTPMethods::post('cvv'),
            new DateTime(UHTTPMethods::post('expiration')),
            UHTTPMethods::post('type'),
            $idUser
        );
        //Inserisco l'oggeto nel db, la validazione dei campi sarà affidata a foundation, gestisco il tutto con un blocco try
        try {
            if(FPersistentManager::getInstance()->create($newCreditCard)!=null) {
                //Operazione effettuata reindirizzo alla schermata di informazioni personali
                echo "Operazioe avvenuta con successo";
                print_r($_SESSION);
            } 
        } catch (Exception $e) {
            //Se ci sono stati errori, reindirizzo alla pagina di errore
            //$view->showCreditCardError();
            echo "Operazione non effettuata: " . $e->getMessage();
            }
        
    }

    /**
     * Function for delete an existing credit card
     * 
     * @param $idCreditCard, recived by the HTTP POST request sended by user when press "delete"
     */
    public function deleteCreditcard($idCreditCard) {
        //id della carta direttamente nella richiesta post quando di preme su "delete"
        FPersistentManager::getInstance()->delete($idCreditCard, FCreditCard::class);
        //reindirizzo alla pagina informazioni utente con la carta rimossa correttamente
        header("Informazioni Personali");
    }

























































}