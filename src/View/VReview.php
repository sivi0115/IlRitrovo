<?php

namespace View;

use Smarty\Smarty;

/**
 * Class View VReview
 * Load all Review's tpl via Smarty
 */
class VReview {
    /**
     * Function to show user's reviews page
     * 
     * @param array $allReviews
     */
    public function showReviewsUserPage(array $allReviews) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('allReviews', $allReviews);
        $smarty->display('review.tpl');
    }

    /**
     * Function to show admin's reviews page
     * 
     * @param array $allReviews
     * @param null|int showReplyForm
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