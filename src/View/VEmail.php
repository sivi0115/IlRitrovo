<?php


namespace View;
use Smarty\Smarty;
use DateTime;
use Entity\ECreditCard;
use Entity\EReview;

class VEmail {
    /**
     * Function to show email tables tpl
     */
    public function showTablesEmail(array $data) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('data', $data);
        return $smarty->fetch('emailTables.tpl');
    }

    /**
     * Function to show email rooms tpl
     */
    public function showRoomsEmail(array $data) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('data', $data);
        return $smarty->fetch('emailRooms.tpl');
    }
}