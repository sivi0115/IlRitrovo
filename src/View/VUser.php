<?php


namespace View;
use Smarty\Smarty;
use DateTime;
use Entity\ECreditCard;
use Entity\EReview;

class VUser {

    public function showProfile(
        string $username,
        string $email,
        string $name,
        string $surname,
        string $birthDate,
        string $phone,
        string $edit_section,
        array $userCreditCards,
        array $userPastReservations,
        array $userFutureReservations,
        ?EReview $userReview
    ) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        // Assegna variabili Smarty
        $smarty->assign('username', $username);
        $smarty->assign('email', $email);
        $smarty->assign('name', $name);
        $smarty->assign('surname', $surname);
        $smarty->assign('birthdate', $birthDate);
        $smarty->assign('phone', $phone);
        $smarty->assign('edit_section', $edit_section);
        $smarty->assign('cards', $userCreditCards);
        $smarty->assign('pastReservations', $userPastReservations);
        $smarty->assign('futureReservations', $userFutureReservations);
        $smarty->assign('review', $userReview);
        //Assegna carte di credito a Smarty

        // Mostra il template
        $smarty->display('userProfile.tpl');
    }

    public function showReviewsUserPage(array $allReviews) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        //Assegna variabili smarty
        $smarty->assign('allReviews', $allReviews);

        //Mostra il template
        $smarty->display('review.tpl');
    }
}
