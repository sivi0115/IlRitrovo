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
    
    public function showSummary(string $timeFrame, int $people, DateTime $reservationDate, string $comment) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('$reservationData.timeFrame', $timeFrame);
        $smarty->assign('$reservationData.guests', $people);
        $smarty->assign('$reservationData.reservationDate', $reservationDate);
        $smarty->assign('reservationDate.comment', $comment);
    }

    public function showAvaliableTablesList(array $avaliableTables) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('availableTables', $avaliableTables);

        $smarty->display('tableReservation2.tpl');
    }



    
}