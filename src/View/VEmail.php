<?php

namespace View;

use Smarty\Smarty;

/**
 * Class View VEmail
 * Load all Email's tpl via Smarty
 */
class VEmail {
    /**
     * Function to show email tables tpl
     * 
     * @param array $data, informations to send
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
     * 
     * @param array $data, informations to send
     */
    public function showRoomsEmail(array $data) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('data', $data);
        return $smarty->fetch('emailRooms.tpl');
    }
}