<?php

namespace Controller;

use DateTime;
use Entity\TimeFrame;
use Entity\StatoPagamento;
use Entity\EExtra;
use Entity\EPayment;
use Entity\EReservation;
use Foundation\FCreditCard;
use Foundation\FExtra;
use Foundation\FPersistentManager;
use Foundation\FReservation;
use Foundation\FRoom;
use Foundation\FTable;
use Foundation\FUser;
use Controller\CUser;
use View\VError;
use View\VReservation;
use View\VUser;
use Utility\UEmail;
use Utility\UHTTPMethods;
use Utility\USessions;

/**
 * Class Controller CReservation
 * Manages all the Reservation's main use cases
 */
class CReservation {
    /**
     * Construct
     */
    public function __construct() {
    }

    /**
     * Function to show table reservation's forms (step1)
     */
    public function showTableForm() {
    $viewU= new VUser();
    $viewR=new VReservation();
    $session = USessions::getIstance();
    if($isLogged=CUser::isLogged()) {
        $idUser=$session->readValue('idUser');
    }
    $viewU->showUserHeader($isLogged);
    $viewR->showTableForm();
    }

    /**
     * Function to find available tables to show to the user (step2)
     * 
     * @throws Excpetion if errors occured
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
        $reservationDate=$reservation->getReservationDate();
        $timeFrame=$reservation->getReservationTimeFrame();
        $people=$reservation->getPeople();
        $comment=$reservation->getComment();
        $avaliableTables=FPersistentManager::getInstance()->getAvaliableTables($reservationDate, $timeFrame, $people, FReservation::class);
        $session->setValue('timeFrame', $timeFrame);
        $session->setValue('people', $people);
        $session->setValue('date', $reservationDate);
        $session->setValue('comment', $comment);
        $session->setValue('avaliableTables', $avaliableTables);
        $session->setValue('reservation', $reservation);
        $viewU->showUserHeader($isLogged);
        $viewR->showSummaryAndAvaliableTables($timeFrame, $people, $reservationDate, $comment, $avaliableTables);
    }

    /**
     * Function to show summary and selected table (step3)
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
        $session->setValue('selectedTable', $selectedTable);
        $viewU->showUserHeader($isLogged);
        $viewR->showFullSummary($timeFrame, $people, $date, $comment, $selectedTable);
    }

    /**
     *Function to finalize and add a new table reservation in db, and also send an email to the user
     */
    public function checkTableReservation() {
        $session=USessions::getIstance();
        $session->startSession();
        $reservation=$session->readValue('reservation');
        $selectedTable=$session->readValue('selectedTable');
        $reservation->setIdtable($selectedTable);
        $reservation->setCreationTime(new DateTime());
        $reservation->setState('confirmed');
        FPersistentManager::getInstance()->create($reservation);
        $user=FPersistentManager::getInstance()->read($session->readValue('idUser'), FUser::class);
        $email=$user->getEmail();
        $data=[
            'Date'=>$reservation->getReservationDate(),
            'TimeFrame'=>$reservation->getReservationTimeFrame(),
            'People'=>$reservation->getPeople(),
            'SelectedTable'=>$selectedTable,
            'Comment'=>$reservation->getComment()
        ];
        UEmail::sendConfirmation($email, $data, $reservation->getIdTable());
        $session->deleteValue('reservation');
        $session->deleteValue('timeFrame');
        $session->deleteValue('people');
        $session->deleteValue('date');
        $session->deleteValue('comment');
        $session->deleteValue('avaliableTables');
        $session->setValue('triggerPopup', true);
        header("Location: /IlRitrovo/public/User/showHomePage");
        exit;
    }

    /**
     * Function to show room reservation's forms (step1)
     */
    public function showRoomForm() {
        $allExtras=FPersistentManager::getInstance()->readAll(FExtra::class);
        $viewU=new VUser();
        $viewR=new VReservation();
        $session = USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $viewU->showUserHeader($isLogged);
        $viewR->showRoomForm($allExtras);
    }

    /**
     * Function to find available rooms to show to the user (step2)
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
        $selectedExtras=[];
        if(UHTTPMethods::post('extras')) {
            foreach(UHTTPMethods::post('extras') as $id) {
                $id=(int) $id;
                $extra=FPersistentManager::getInstance()->read($id, FExtra::class);
                if($extra!==null) {
                    $selectedExtras[]=$extra;
                }
            }
        }
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
        $reservationDate=$reservation->getReservationDate();
        $timeFrame=$reservation->getReservationTimeFrame();
        $people=$reservation->getPeople();
        $comment=$reservation->getComment();
        $totalPriceExtra=$reservation->getTotPrice();
        $availableRooms=FPersistentManager::getInstance()->getAvailableRooms($reservationDate, $timeFrame, $people, FReservation::class);
        $session->setValue('timeFrame', $timeFrame);
        $session->setValue('people', $people);
        $session->setValue('date', $reservationDate);
        $session->setValue('comment', $comment);
        $session->setValue('extras', $selectedExtras);
        $session->setValue('totPrice', $totalPriceExtra);
        $session->setValue('availableRooms', $availableRooms);
        $session->setValue('reservation', $reservation);
        $viewU->showUserHeader($isLogged);
        $viewR->showSummaryAndAvailableRooms($timeFrame, $people, $reservationDate, $comment, $selectedExtras, $totalPriceExtra, $availableRooms);
    }

    /**
     * Function to show summary with total price (extra+room) and available payment methods (step3)
     */
    public function dataRoomReservation() {
        $viewU=new VUser();
        $viewR=new VReservation();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $timeFrame=$session->readValue('timeFrame');
        $people=$session->readValue('people');
        $reservationDate=$session->readValue('date');
        $comment=$session->readValue('comment');
        $selectedExtras=$session->readValue('extras');
        $totalPriceExtra=$session->readValue('totPrice');
        $idSelectedRoom = isset($_POST['idRoom']) ? UHTTPMethods::post('idRoom') : null;
        if (!$idSelectedRoom) {
            $idSelectedRoom = $session->readValue('idRoom');
        } else {
            $session->setValue('idRoom', $idSelectedRoom);
        }
        $selectedRoom=FPersistentManager::getInstance()->read((int)$idSelectedRoom, FRoom::class);
        $roomTax=$selectedRoom->getTax();
        $extraAndRoomPrice=$totalPriceExtra+$roomTax;
        $userCreditCards=FPersistentManager::getInstance()->readCreditCardsByUser($idUser, FCreditCard::class);
        $session->setValue('extraAndRoomPrice', $extraAndRoomPrice);
        $viewU->showUserHeader($isLogged);
        $viewR->showSummaryAndPaymentMethods($timeFrame, $people, $reservationDate, $comment, $selectedExtras, $selectedRoom, $extraAndRoomPrice, $userCreditCards);
    }

    /**
     * Function to show full summary and the credit card selected by the user for the payment (step4)
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
        $selectedRoom=FPersistentManager::getInstance()->read($idSelectedRoom, FRoom::class);
        $idSelectedCard=UHTTPMethods::post('selectedCardId');
        $selectedCard=FPersistentManager::getInstance()->read((int)$idSelectedCard, FCreditCard::class);
        if (!$selectedCard) {
            VError::showError('Credit Card is necessary to complete the reservation');
            exit;
        }
        $session->setValue('idSelectedCard', $idSelectedCard);
        $viewU->showUserHeader($isLogged);
        $viewR->showSummaryRoomAndPaymentMethodes($timeFrame, $people, $reservationDate, $comment, $selectedExtras, $selectedRoom, $extraAndRoomPrice, $selectedCard);
    }

    /**
     * Function to finalize the reservation and add in db. Also to add in db a new payment and send an email to the user
     */
    public function checkPayment() {
        $viewU=new VUser();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $reservation=$session->readValue('reservation');
        $idRoom=$session->readValue('idRoom');
        $emailSelectedRoom=FPersistentManager::getInstance()->read($idRoom, FRoom::class);
        $SelectedRoomEmail=$emailSelectedRoom->getAreaName();
        $idSelectedCard=$session->readValue('idSelectedCard');
        $extraAndRoomPrice=$session->readValue('extraAndRoomPrice');
        $reservation->setIdUser($idUser);
        $reservation->setIdRoom($idRoom);
        $reservation->setCreationTime(new DateTime());
        $reservation->setState('approved');
        $reservation->setTotPrice($extraAndRoomPrice);
        $newIdReservation=FPersistentManager::getInstance()->create($reservation);
        $newPayment=new EPayment(
            null,
            $idSelectedCard,
            $newIdReservation,
            $extraAndRoomPrice,
            new DateTime(),
            StatoPagamento::COMPLETATO
        );
        FPersistentManager::getInstance()->create($newPayment);
        $user=FPersistentManager::getInstance()->read($session->readValue('idUser'), FUser::class);
        $user=FPersistentManager::getInstance()->read($session->readValue('idUser'), FUser::class);
        $email=$user->getEmail();
        $data=[
            'Date'=>$reservation->getReservationDate(),
            'TimeFrame'=>$reservation->getReservationTimeFrame(),
            'People'=>$reservation->getPeople(),
            'SelectedRoom'=>$SelectedRoomEmail,
            'Comment'=>$reservation->getComment()
        ];
        UEmail::sendConfirmation($email, $data, $reservation->getIdTable());
        $session->deleteValue('reservation');
        $session->deleteValue('timeFrame');
        $session->deleteValue('people');
        $session->deleteValue('comment');
        $session->deleteValue('date');
        $session->deleteValue('extras');
        $session->deleteValue('totPrice');
        $session->deleteValue('availableRooms');
        $session->deleteValue('extraAndRoomPrice');
        $session->deleteValue('idSelectedCard');
        $session->setValue('triggerPopup', true);
        header("Location: /IlRitrovo/public/User/showHomePage");
        exit;
    }

    /**
     * Function to load all Room and Table Reservation for admin home page
     * 
     * @return array $comingTableReservation, $comingRoomReservation
     */
    public static function getStructuredReservationsForAdmin(): array {
        $allReservations = FPersistentManager::getInstance()->readAll(FReservation::class);
        $comingTableReservations = [];
        $comingRoomReservations = [];
        foreach ($allReservations as $reservation) {
            $user = FPersistentManager::getInstance()->read($reservation->getIdUser(), FUser::class);
            if ($user) {
                $reservation->setUsername($user->getUsername());
            }
            if ($reservation->getIdTable() !== null) {
                $table = FPersistentManager::getInstance()->read($reservation->getIdTable(), FTable::class);
                if ($table) {
                    $reservation->setAreaName($table->getAreaName());
                }
                $comingTableReservations[] = $reservation;
            } elseif ($reservation->getIdRoom() !== null) {
                $room = FPersistentManager::getInstance()->read($reservation->getIdRoom(), FRoom::class);
                if ($room) {
                    $reservation->setAreaName($room->getAreaName());
                }
                $comingRoomReservations[] = $reservation;
            }
        }
    return [$comingTableReservations, $comingRoomReservations];
    }

    /**
     * Function to return all of user's past reservations
     * 
     * @return array $pastUserReservations
     */
    public static function getPastReservations(): array {
        $session = USessions::getIstance();
        $idUser = $session->readValue('idUser');
        $allUserReservations = FPersistentManager::getInstance()->readReservationsByUserId($idUser, FReservation::class);
        $pastUserReservations = [];
        $now = new DateTime();
        foreach ($allUserReservations as $reservation) {
            $reservationDateStr = $reservation->getReservationDate();
            // Converto la stringa in DateTime
            $reservationDate = DateTime::createFromFormat('Y-m-d H:i:s', $reservationDateStr);
            if ($reservationDate !== false && $reservationDate < $now) {
                $pastUserReservations[] = $reservation;
            }
        }
    return $pastUserReservations;
    }
}