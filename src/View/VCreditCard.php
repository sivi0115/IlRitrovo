<?php

namespace View;

use Smarty\Smarty;
use DateTime;
use Entity\ECreditCard;
use Entity\EUser;



class VCreditCard {
    /**
     * Function to show new Credit Card form
     */
    public function showAddCreditCardUserProfile() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $allowedTypes=['Visa', 'Mastercard', 'American Express', 'Maestro', 'V-Pay', 'PagoBANCOMAT'];
        $smarty->assign('allowedTypes', $allowedTypes);
        $smarty->display('addCreditCardUserProfile.tpl');
    }
}