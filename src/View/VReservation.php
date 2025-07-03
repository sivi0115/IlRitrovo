<?php

namespace View;

use Entity\ECreditCard;
use Entity\ERoom;
use Smarty\Smarty;

/**
 * Class View VReservation
 * Load all Reservation's tpl via Smarty
 */
class VReservation {
    /**
     * Function to show Reservation form
     */
    public function showTableForm() {
        $smarty=new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->display('tableReservation1.tpl');
    }
    
    /**
     * Function to show summary and all available tables
     * 
     * @param string $timeFrame
     * @param int $people
     * @param string $reservationDate
     * @param string $comment
     * @param array $availableTables
     */
    public function showSummaryAndAvailableTables(string $timeFrame, int $people, string $reservationDate, string $comment, array $availableTables) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('timeFrame', $timeFrame);
        $smarty->assign('people', $people);
        $smarty->assign('reservationDate', $reservationDate);
        $smarty->assign('comment', $comment);
        $smarty->assign('availableTables', $availableTables);
        $smarty->display('tableReservation2.tpl');
    }
    
    /**
     * Function to show full summary Page
     * 
     * @param string $timeFrame
     * @param int $people
     * @param string $reservationDate
     * @param string $comment
     * @param int $idTable
     */
    public function showFullSummary(string $timeFrame, int $people, string $reservationDate, string $comment, int $idTable) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('timeFrame', $timeFrame);
        $smarty->assign('people', $people);
        $smarty->assign('reservationDate', $reservationDate);
        $smarty->assign('comment', $comment);
        $smarty->assign('idTable', $idTable);
        $smarty->display('tableReservation3.tpl');
    }

    /**
     * Function to show Reservation Room form's with associated extras to select
     * 
     * @param array $allExtras
     */
    public function showRoomForm(array $allExtras) {
        $smarty=new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('extras', $allExtras);
        $smarty->display('roomReservation1.tpl');
    }

    /**
     * Function to show summary and all available Rooms
     * 
     * @param string $timeFrame
     * @param int $people
     * @param string $reservationDate
     * @param string $comment
     * @param array $selectedExtras
     * @param $totPriceExtra
     * @param array $availableRooms
     */
    public function showSummaryAndAvailableRooms(string $timeFrame, int $people, string $reservationDate, string $comment, array $selectedExtras, float $totPriceExtra, array $availableRooms, ) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('timeFrame', $timeFrame);
        $smarty->assign('people', $people);
        $smarty->assign('reservationDate', $reservationDate);
        $smarty->assign('comment', $comment);
        $smarty->assign('extras', $selectedExtras);
        $smarty->assign('totPriceExtra', $totPriceExtra);
        $smarty->assign('availableRooms', $availableRooms);
        $smarty->display('roomReservation2.tpl');
    }

    /**
     * Function to show summary and available payment methods
     * 
     * @param string $timeFrame
     * @param int $people
     * @param string $reservationDate
     * @param string $comment
     * @param array $selectedExtras
     * @param ERoom $selectedRoom
     * @param float $extraAndRoomPrice
     * @param array $userCreditCards
     */
    public function showSummaryAndPaymentMethods( string $timeFrame, int $people, string $reservationDate, string $comment, array $selectedExtras, ERoom $selectedRoom, float $extraAndRoomPrice, array $userCreditCards) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('timeFrame', $timeFrame);
        $smarty->assign('people', $people);
        $smarty->assign('reservationDate', $reservationDate);
        $smarty->assign('comment', $comment);
        $smarty->assign('extras', $selectedExtras);
        $smarty->assign('selectedRoom', $selectedRoom);
        $smarty->assign('totalPrice', $extraAndRoomPrice);
        $smarty->assign('userCreditCards', $userCreditCards);
        $smarty->display('roomReservation3.tpl');
    }

    /**
     * Function to show full summary include selected payment method
     * 
     * @param string $timeFrame
     * @param int $people
     * @param string $reservationDate
     * @param string $comment
     * @param array $selectedExtras
     * @param ERoom $selectedRoom
     * @param float $extraAndRoomPrice
     * @param ECreditCard $selectedCreditCard
     */
    public function showSummaryRoomAndPaymentMethodes(string $timeFrame, int $people, string $reservationDate, string $comment, array $selectedExtras, ERoom $selectedRoom, float $extraAndRoomPrice, ECreditCard $selectedCard) {
    $smarty = new Smarty();
    $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
    $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
    $smarty->assign('timeFrame', $timeFrame);
    $smarty->assign('people', $people);
    $smarty->assign('reservationDate', $reservationDate);
    $smarty->assign('comment', $comment);
    $smarty->assign('extras', $selectedExtras);
    $smarty->assign('selectedRoom', $selectedRoom);
    $smarty->assign('totalPrice', $extraAndRoomPrice);
    $smarty->assign('selectedCard', $selectedCard);
    $smarty->display('roomReservation4.tpl');
    }
}