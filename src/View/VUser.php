<?php


namespace View;
use Smarty\Smarty;
use DateTime;
use Entity\ECreditCard;
use Entity\EReview;

class VUser {
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
     * Function to show admin's header
     */
    public function showAdminHeader(bool $isLogged) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('isLogged', $isLogged);
        $smarty->display('headerAdmin.tpl');
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
     * Function to show logged admin home page
     */
    public function showLoggedAdminHomePage(bool $isLogged, array $comingTableReservations, array $comingRoomReservations) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');
        $smarty->assign('isLogged', $isLogged);
        $smarty->assign('comingTableReservations', $comingTableReservations);
        $smarty->assign('comingRoomReservations', $comingRoomReservations);
        $smarty->display('adminHome.tpl');
    }

    /**
     * Function to show non unlogged user home page
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

    /**
     * Function to show edit forms for editing personal data
     */
    public function showEditProfileData() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->display('editProfileData.tpl');
    }

    /**
     * Function to show edit forms for editing metadata like email username and password
     */
    public function showEditProfileMetadata() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->display('editProfileMetadata.tpl');
    }

    /**
     * Function to show disabled cookies warning
     */
    public function showDisabledCookies() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->display('disabledCookies.tpl');
    }

    /**
     * Function to show menu page
     */
    public function showMenuPage() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->display('menu.tpl');
    }

    /**
     * Function to show Rooms page
     */
    public function showRoomsPage() {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->display('rooms.tpl');
    }

    /**
     * Function to show User's Page
     */
    public function showUsersPage(array $blocked_user, array $allUsers) {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../Smarty/tpl/');
        $smarty->setCompileDir(__DIR__ . '/../Smarty/templates_c/');

        $smarty->assign('blocked_user', $blocked_user);
        $smarty->assign('allUsers', $allUsers);
        $smarty->display('adminUsers.tpl');
    }
}
