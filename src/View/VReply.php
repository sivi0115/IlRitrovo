<?php

namespace View;

use Smarty\Smarty;

/**
 * Class View VReply
 * Load all Reply's tpl via Smarty
 */
class VReply {
    /**
     * Function to show reply form
     * 
     * @param int $idReview, ID of the Review to reply
     */
    public function showReplyForm(int $idReview) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('showReplyForm', $idReview);
        $smarty->display('adminReview.tpl');
    }
}