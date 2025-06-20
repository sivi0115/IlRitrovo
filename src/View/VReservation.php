<?php

namespace View;
use DateTime;
use Smarty\Smarty;
use Entity\EReservation;
use Entity\EUser;
use Foundation\FPersistentManager;




class VReservation {

    /**
     * Mostra le form per la prenotazione di un tavolo
     */
    public function showTableForm() {
        $smarty=new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->display('tableReservation1.tpl');
    }
    
    /**
     * Mostra la pagina dove si vede la mappa dei tavoli e un selettore per quelli disponibili
     */
    public function showSummaryAndAvaliableTables(string $timeFrame, int $people, string $reservationDate, string $comment, array $avaliableTables) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('timeFrame', $timeFrame);
        $smarty->assign('people', $people);
        $smarty->assign('reservationDate', $reservationDate);
        $smarty->assign('comment', $comment);
        $smarty->assign('availableTables', $avaliableTables);
        $smarty->display('tableReservation2.tpl');
    }
    
    /**
     * Mostra la pagina di riepilogo completo 
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
     * Mostra le form per la prenotazione di una sala con gli extra selezionabili
     */
    public function showRoomForm(array $allExtras) {
        $smarty=new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('extras', $allExtras);
        $smarty->display('roomReservation1.tpl');
    }

    /**
     * Mostra la pagina con la mappa delle sale totali e un selettore per scegliere tra le disponibili
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
}