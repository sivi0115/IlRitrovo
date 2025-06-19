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
use Controller\CUser;

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
    $view = new VReservation();
    $session = USessions::getIstance();
    // Se utente loggato, salva idUser in sessione (se non già presente)
    if (CUser::isLogged()) {
        if (!$session->isValueSet('idUser')) {
            $userId = $session->readValue('idUser'); // Prova a leggere l'idUser
            // Se per qualche motivo non c'è, recuperalo dalla fonte giusta (es. DB, token, ecc)
            // Altrimenti potresti settarlo qui se già disponibile (dipende dalla logica)
            // Ma probabilmente qui basta solo confermare che è già in sessione
        }
    }
    // Mostro la form
    $view->showTableForm();
}

    /**
     * Function to find available tables to show to the user based on the data entered
     * 
     * @throws Excpetion if detected errors
     */
    public function showValidTable() {
        $view=new VReservation(); 
        $session=USessions::getIstance();
        $session->startSession();
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
            (int)UHTTPMethods::post('people'),
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
        $view->showSummaryAndAvaliableTables($timeFrame, $people, $reservationDate, $comment, $avaliableTables);

    }

    /**
     * Function to show summary and avaliable tables, and allow user to select the wished table
     */
    public function dataTableReservation() {

        //Mostrare lo spet3 
        $view=new VReservation(); 
        $session=USessions::getIstance();
        $session->startSession();
        $timeFrame =$session->readValue('timeFrame');
        $people=$session->readValue('people');
        $date=$session->readValue('date');
        $comment=$session->readValue('comment');
        $selectedTable=UHTTPMethods::post('idTable');
        //Aggiungo il tavolo selezionato in sessione
        $session->setValue('selectedTable', $selectedTable);
        //Passo le informazioni a View
        $view->showFullSummary($timeFrame, $people, $date, $comment, $selectedTable);
    }

    /**
     * 
     */
    public function checkTableReservation() {
        $session=USessions::getIstance();
        $session->startSession();
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
        header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');
    }

}