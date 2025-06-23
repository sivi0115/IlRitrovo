<?php


namespace View;
use Smarty\Smarty;
use DateTime;
use Entity\ECreditCard;
use Entity\EReview;

class VUser {
    /**
     * Function to show login/register pop up
     */
    public function showLoginRegisterPopUp(bool $isLogged) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('isLogged', $isLogged);
        $smarty->display('headerUser.tpl');
    }

    /**
     * Function to show logged user home page
     */
    public function showLoggedUserHomePage() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->display('home.tpl');
    }

    /**
     * Function to show admin's header
     */
    public function showAdminHeader() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('admin', true);
        $smarty->display('headerAdmin.tpl');

    }

    /**
     * Function to show logged admin home page
     */
    public function showLoggedAdminHomePage() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->display('headerAdmin.tpl');
        $smarty->display('adminHome.tpl');
    }










    /**
     * Function to show user's info profile
     */
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
        // Mostra il template
        $smarty->display('userProfile.tpl');
    }
}
