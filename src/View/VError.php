<?php

namespace View;

use Smarty\Smarty;

/**
 * Class View VError
 * Load all Error's tpl via Smarty
 */
class VError {
    /**
     * Function to show Error Page during any operation
     * 
     * @param string $message, the message to show
     */
    public static function showError($message) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('errorMessage', $message);
        $smarty->display('error.tpl');
    }
}