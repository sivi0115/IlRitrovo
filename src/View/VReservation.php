<?php

namespace View;
use DateTime;
use Smarty\Smarty;
use Entity\EReservation;
use Entity\EUser;



class VReservation {

    public function showTableForm() {
        $smarty=new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->display('tableReservation1.tpl');
    }
    
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
}