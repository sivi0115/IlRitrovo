<?php

namespace View;

use Smarty\Smarty;

/**
 * Class View VCreditCard
 * Load all CreditCards' tpl via Smarty
 */
class VCreditCard {
    /**
     * Function to show new Credit Card form from user profile
     */
    public function showAddCreditCardUserProfile() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $allowedTypes=['Visa', 'Mastercard', 'American Express', 'Maestro', 'V-Pay', 'PagoBANCOMAT'];
        $smarty->assign('allowedTypes', $allowedTypes);
        $smarty->display('addCreditCardUserProfile.tpl');
    }

    /**
     * Function to show new Credit Card form from step 3 reservation
     */
    public function showAddCreditCardStep3() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $allowedTypes=['Visa', 'Mastercard', 'American Express', 'Maestro', 'V-Pay', 'PagoBANCOMAT'];
        $smarty->assign('allowedTypes', $allowedTypes);
        $smarty->display('addCreditCardStep3.tpl');
    }
}