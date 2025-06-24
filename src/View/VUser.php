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
     * Function to show header
     */
    public function showUserHeader(bool $isLogged) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('isLogged', $isLogged);
        $smarty->display('headerUser.tpl');
    }

    /**
     * Function to show logged user home page
     */
    public function showLoggedUserHomePage(bool $isLogged) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('isLogged', $isLogged);
        $smarty->display('home.tpl');
    }

    /**
     * Function to show non logged user home page
     */
    public function showUserHomePage(bool $isLogged) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('isLogged', $isLogged);
        $smarty->display('home.tpl');
    }

    /**
     * Function to show login page
     */
    public function showLoginForm() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->display("login.tpl");
    }

    /**
     * Function to show signup page with forms
     */
    public function showSignUpForm() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->display("signUp.tpl");
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
