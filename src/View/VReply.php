<?php

namespace View;

namespace View;
use Smarty\Smarty;
use DateTime;
use Entity\ECreditCard;
use Entity\EReview;

class VReply {
    /**
     * Function to show reply form
     */
    public function showReplyForm(int $idReview) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('showReplyForm', $idReview);
        $smarty->display('adminReview.tpl');
    }
}