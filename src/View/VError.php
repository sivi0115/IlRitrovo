<?php

namespace View;
use Smarty\Smarty;

class VError {
    
    public static function showError($message) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('errorMessage', $message);
        $smarty->display('error.tpl');
    }
}