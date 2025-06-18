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
     * 
     * @throws Excpetion if detected errors
     */
    public function showValidTable() {
        $view=new VUser(); //Da cambiare in VReservation
        $session=USessions::getIstance();
        $idUser=$session->readValue('idUser');
        $reservation=new EReservation(
            null,
            $idUser,
            null,
            null,
            null,
            new DateTime(UHTTPMethods::post('reservationDate')),
            TimeFrame::from(UHTTPMethods::post('timeFrame')),
            'pending',
            UHTTPMethods::post('people'),
            UHTTPMethods::post('comment'),
            [],
            0
        );
        if ($reservation->getReservationDate()>new DateTime()) {
            throw new Exception("Reservation date can't be in future");
        }
        //Estraggo dall'oggetto le informazioni necessarie a caricare i tavoli disponibili in base alla richiesta
        $reservationDate=$reservation->getReservationDate();
        $timeFrame=$reservation->getReservationTimeFrame();
        $people=$reservation->getPeople();
        $comment=$reservation->getComment();
        //Cerco dei tavoli disponibili in base a questi parametri e li mostro all'utente
        $avaliableTables=FPersistentManager::getInstance()->getAvaliableTables($reservationDate, $timeFrame, $people, FReservation::class);
        //echo (json_encode($avaliableTables, JSON_PRETTY_PRINT));
        //Salvo i dati in sessione
        $session->setValue('timeFrame', $timeFrame);
        $session->setValue('people', $people);
        $session->setValue('date', $reservationDate);
        $session->setValue('comment', $comment);
        $session->setValue('avaliableTables', $avaliableTables);
        $session->setValue('reservation', $reservation);
        //Passo i parametri a view
        $view->showSummary($timeFrame, $people, $reservationDate, $comment);
        $view->showAvaliableTablesList($avaliableTables);
    }

    /**
     * Function to show summary and avaliable tables, and allow user to select the wished table
     */
    public function dataTableReservation() {

        //Mostrare lo spet3 
        $view=new VUser(); //Da cambiare in VReservation
        $session=USessions::getIstance();
        $timeFrame=$session->readValue('timeFrame');
        $people=$session->readValue('people');
        $date=$session->readValue('date');
        $comment=$session->readValue('comment');
        $selectedTable=UHTTPMethods::post('selectedTable');
        //Aggiungo il tavolo selezionato in sessione
        $session->setValue('selectedTable', $selectedTable);
        //Passo le informazioni a View
        $view->showFullSummary();
    }

    /**
     * 
     */
    public function checkTableReservation() {
        $session=USessions::getIstance();
        //Carico dalla sessione tutte le informazioni per aggiornare l'oggetto EReservation con i dati mancanti
        $reservation=$session->readValue('reservation');
        $selectedTable=$session->readValue('selectedTable');
        //Aggiorno l'oggetto EReservation
        $reservation->setIdtable($selectedTable);
        $reservation->setCreationTime(new DateTime());
        $reservation->setState('confirmed');
        //Aggiungo su db la nuova prenotazione
        FPersistentManager::getInstance()->create($reservation);
        //Pulisci la sessione
        $session->deleteValue('reservation');
        $session->deleteValue('timeFrame');
        $session->deleteValue('people');
        $session->deleteValue('date');
        $session->deleteValue('comment');
        $session->deleteValue('avaliableTables');
        //Reindirizzo alla home page
        header("Home Page");
    }

}