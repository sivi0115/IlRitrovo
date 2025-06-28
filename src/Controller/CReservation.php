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
use Entity\EExtra;
use Foundation\FCreditCard;
use Foundation\FDatabase;
use Foundation\FExtra;
use Foundation\FRoom;
use Entity\EPayment;
use Entity\StatoPagamento;
use Foundation\FPayment;


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
    $viewU= new VUser();
    $viewR=new VReservation();
    $session = USessions::getIstance();
    if($isLogged=CUser::isLogged()) {
        $idUser=$session->readValue('idUser');
    }
    // Carico l'header e la pagina con lo step 1 della prenotazione di un tavolo
    $viewU->showUserHeader($isLogged);
    $viewR->showTableForm();
    }

    /**
     * Function to find available tables to show to the user based on the data entered
     * 
     * @throws Excpetion if detected errors
     */
    public function showValidTable() {
        $viewU=new VUser();
        $viewR=new VReservation(); 
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
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
        $viewU->showUserHeader($isLogged);
        $viewR->showSummaryAndAvaliableTables($timeFrame, $people, $reservationDate, $comment, $avaliableTables);

    }

    /**
     * Function to show summary and avaliable tables, and allow user to select the wished table
     */
    public function dataTableReservation() {
        $viewU=new VUser();
        $viewR=new VReservation(); 
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $timeFrame =$session->readValue('timeFrame');
        $people=$session->readValue('people');
        $date=$session->readValue('date');
        $comment=$session->readValue('comment');
        $selectedTable=UHTTPMethods::post('idTable');
        //Aggiungo il tavolo selezionato in sessione
        $session->setValue('selectedTable', $selectedTable);
        //Passo le informazioni a View
        $viewU->showUserHeader($isLogged);
        $viewR->showFullSummary($timeFrame, $people, $date, $comment, $selectedTable);
    }

    /**
     *Function to finalize and adding a new Table Reservation in db
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
        header("Location: /IlRitrovo/public/User/showHomePage");
    }

    /**
     * Function to show Reservation Room form
     */
    public function showRoomForm() {
        $allExtras=FPersistentManager::getInstance()->readAll(FExtra::class);
        $viewU=new VUser();
        $viewR=new VReservation();
        $session = USessions::getIstance();
        // Se utente loggato, salva idUser in sessione (se non già presente)
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }

        $viewU->showUserHeader($isLogged);
        $viewR->showRoomForm($allExtras);
    }

    /**
     * Function to find available tables to show to the user based on the data entered
     * 
     * @throws Excpetion if something goes wrong
     */
    public function showValidRooms() {
        $viewU=new VUser();
        $viewR=new VReservation();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        //Carico gli extra selezionati dall'utente in $selectedExtras[]
        $selectedExtras=[];
        if(UHTTPMethods::post('extras')) {
            foreach(UHTTPMethods::post('extras') as $id) {
                $id=(int) $id; //cast esplicito a intero. Gli id in $_POST sono stringhe
                $extra=FPersistentManager::getInstance()->read($id, FExtra::class);
                if($extra!==null) {
                    $selectedExtras[]=$extra;
                }
            }
        }
        //Calcolo il prezzo totale degli extra selezionati dall'utente
        $totalPriceExtra=0;
        if(!empty($selectedExtras)) {
            foreach($selectedExtras as $extra) {
                if ($extra instanceof EExtra) {
                    $totalPriceExtra += $extra->getPriceExtra();
                }
            }
        }
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
            $selectedExtras,
            $totalPriceExtra
        );
        //Estraggo dall'oggetto le informazioni necessarie a caricare le sale disponibili in base alla richiesta
        $reservationDate=$reservation->getReservationDate();
        $timeFrame=$reservation->getReservationTimeFrame();
        $people=$reservation->getPeople();
        $comment=$reservation->getComment();
        $totalPriceExtra=$reservation->getTotPrice();
        //Cerco delle sale disponibili in base a questi parametri e le mostro all'utente
        $availableRooms=FPersistentManager::getInstance()->getAvailableRooms($reservationDate, $timeFrame, $people, FReservation::class);
        //Salvo i dati in sessione
        $session->setValue('timeFrame', $timeFrame);
        $session->setValue('people', $people);
        $session->setValue('date', $reservationDate);
        $session->setValue('comment', $comment);
        $session->setValue('extras', $selectedExtras);
        $session->setValue('totPrice', $totalPriceExtra);
        $session->setValue('availableRooms', $availableRooms);
        $session->setValue('reservation', $reservation);
        //Passo i parametri a View 
        $viewU->showUserHeader($isLogged);
        $viewR->showSummaryAndAvailableRooms($timeFrame, $people, $reservationDate, $comment, $selectedExtras, $totalPriceExtra, $availableRooms);
    }

    /**
     * Function to show summary with total price (extra+room) and payment methods
     */
    public function dataRoomReservation() {
        $viewU=new VUser();
        $viewR=new VReservation();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        //Recupero i dati dalla sessione
        $timeFrame=$session->readValue('timeFrame');
        $people=$session->readValue('people');
        $reservationDate=$session->readValue('reservationDate');
        $comment=$session->readValue('comment');
        $selectedExtras=$session->readValue('extras');
        $totalPriceExtra=$session->readValue('totPrice');
        //Prendo l'id della stanza selezionata dalla richiesta post
        $idSelectedRoom = isset($_POST['idRoom']) ? UHTTPMethods::post('idRoom') : null;
        if (!$idSelectedRoom) {
            // fallback alla sessione
            $idSelectedRoom = $session->readValue('idRoom');
        } else {
            // aggiorna la sessione solo se arriva un nuovo id dalla POST
            $session->setValue('idRoom', $idSelectedRoom);
        }
        //Ottengo il prezzo della stanza che sommo al prezzo degli extra (salvato in sessione)
        $selectedRoom=FPersistentManager::getInstance()->read((int)$idSelectedRoom, FRoom::class);
        $roomTax=$selectedRoom->getTax();
        $extraAndRoomPrice=$totalPriceExtra+$roomTax;
        //Carico le carte di credito associate all'utente per visualizzarle
        $userCreditCards=FPersistentManager::getInstance()->readCreditCardsByUser($idUser, FCreditCard::class);
        //Aggiungo in sessione i dati necessari al prossimo step, ossia l'd della stanza selezionata e il prezzo totale
        $session->setValue('extraAndRoomPrice', $extraAndRoomPrice);
        //Passo i valori a View per la visualizzazione del riepilogo dati precedentemente inseriti
        $viewU->showUserHeader($isLogged);
        $viewR->showSummaryAndPaymentMethods($timeFrame, $people, $reservationDate, $comment, $selectedExtras, $selectedRoom, $extraAndRoomPrice, $userCreditCards);
    }

    /**
     * Function to show full summary and the credit card select by the user for the payment
     */
    public function showSummaryRoomAndPaymentForm() {
        $viewU=new VUser();
        $viewR=new VReservation();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $timeFrame=$session->readValue('timeFrame');
        $people=$session->readValue('people');
        $reservationDate=$session->readValue('reservationDate');
        $comment=$session->readValue('comment');
        $selectedExtras=$session->readValue('extras');
        $totalPriceExtra=$session->readValue('totPrice');
        $idSelectedRoom=$session->readValue('idRoom');
        $extraAndRoomPrice=$session->readValue('extraAndRoomPrice');
        //Recupero da db nuovamente la stanza selezionata
        $selectedRoom=FPersistentManager::getInstance()->read($idSelectedRoom, FRoom::class);
        //Recupero dalla richiesta post l'id della carta selezionata per il pagamento
        $idSelectedCard=UHTTPMethods::post('selectedCardId');
        //Recupero la carta da db grazie al suo id
        $selectedCard=FPersistentManager::getInstance()->read((int)$idSelectedCard, FCreditCard::class);
        //Salvo i dati in sessione per il pagamento nello step successivo
        $session->setValue('idSelectedCard', $idSelectedCard);
        //Passo i parametri a view
        $viewU->showUserHeader($isLogged);
        $viewR->showSummaryRoomAndPaymentMethodes($timeFrame, $people, $reservationDate, $comment, $selectedExtras, $selectedRoom, $extraAndRoomPrice, $selectedCard);
    }

    /**
     * Function to create a new Payment in association with the selected credit card in previews step
     */
    public function checkPayment() {
        $viewU=new VUser();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $reservation=$session->readValue('reservation');
        $idRoom=$session->readValue('idRoom');
        $idSelectedCard=$session->readValue('idSelectedCard');
        $extraAndRoomPrice=$session->readValue('extraAndRoomPrice');
        $reservation->setIdUser($idUser);
        $reservation->setIdRoom($idRoom);
        $reservation->setCreationTime(new DateTime());
        $reservation->setState('approved');
        $reservation->setTotPrice($extraAndRoomPrice);
        //Registro la prenotazione su db, verranno salvati anche gli extra associati in extrainreservation
        $newIdReservation=FPersistentManager::getInstance()->create($reservation);
        //Adesso istanzio il pagamento per tenerne traccia
        $newPayment=new EPayment(
            null,
            $idSelectedCard,
            $newIdReservation,
            $extraAndRoomPrice,
            new DateTime(),
            StatoPagamento::COMPLETATO
        );
        //Metto il pagamento su db
        FPersistentManager::getInstance()->create($newPayment);
        //Reindirizzo alla schermata home
        header("Location: /IlRitrovo/public/User/showHomePage");
    }

}