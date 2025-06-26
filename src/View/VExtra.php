<?php

namespace View;
use Smarty\Smarty;
use DateTime;
use Entity\ECreditCard;
use Entity\EReview;
use Entity\EExtra;

class VExtra {
    /**
     * Function to show extra's page
     */
    public function showExtrasPage(array $allExtras) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('allExtras', $allExtras);
        $smarty->assign('show_extra_form', true);
        $smarty->display('adminExtra.tpl');
    }
}

