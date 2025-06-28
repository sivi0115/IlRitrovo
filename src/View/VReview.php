<?php


namespace View;
use Smarty\Smarty;
use DateTime;
use Entity\ECreditCard;
use Entity\EReview;

class VReview {
    /**
     * Function to show user's reviews page
     */
    public function showReviewsUserPage(array $allReviews) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        //Assegna variabili smarty
        $smarty->assign('allReviews', $allReviews);

        //Mostra il template
        $smarty->display('review.tpl');
    }

    /**
     * Function to show admin's reviews page
     */
    public function showReviewsAdminPage(array $allReviews, ?int $showReplyForm=null) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('allReviews', $allReviews);
        if($showReplyForm!==null) {
            $smarty->assign('showReplyForm', $showReplyForm);
        }
        $smarty->display('adminReview.tpl');
    }
}