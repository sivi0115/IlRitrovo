<?php

namespace View;

use Entity\EExtra;
use Smarty\Smarty;

/**
 * Class View VExtra
 * Load all Extra's tpl via Smarty
 */
class VExtra {
    /**
     * Function to show extra's page
     * 
     * @param array $allExtras, array that contain all the extras
     */
    public function showExtrasPage(array $allExtras) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('allExtras', $allExtras);
        $smarty->assign('show_extra_form', true);
        $smarty->display('adminExtra.tpl');
    }

    /**
     * Function to show Extra's edit page
     * 
     * @param EExtra $extra, the Extra to edit
     */
    public function showEditExtraPage(EExtra $extra) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('extra', $extra);
        $smarty->display('editExtraData.tpl');
    }
}

