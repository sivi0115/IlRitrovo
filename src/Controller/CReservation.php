<?php

namespace Controller;

use DateTime;
use Entity\EReservation;
use Entity\TimeFrame;
use Exception;
use Foundation\FPersistentManager;
use Foundation\FReservation;
use Utility\UHTTPMethods;
use Utility\USessions;
use View\VReservation;
use View\VUser;

class CReservation {
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Function to show Reservation Table forms
     */
    public function showTableForm() {
        $view=new VUser(); //da cambiare in VReservation
        $view->showTableForm();
    }

    /**
     * Function to find available tables to show to the user based on the data entered
     */
    public function showValidTable(): array {
        $session=USessions::getIstance();
        $idUser=$session->readValue('idUser');
        $reservation=new EReservation(
            null,
            $idUser,
            null,
            null,
            new DateTime(UHTTPMethods::post('creationTime')),
            new DateTime(UHTTPMethods::post('reservationDate')),
            TimeFrame::from(UHTTPMethods::post('timeFrame')),
            'pending',
            UHTTPMethods::post('people'),
            UHTTPMethods::post('comment'),
            null,
            0
        );
        //Cerco dei tavoli disponibili in base a questi parametri e li mostro all'utente
        $avalibleTables=FPersistentManager::getInstance()->getAvalibleTables();

    }

    /**
     * Function to 
     */

}