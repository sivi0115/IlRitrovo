<?php

namespace Controller;


use Utility\UCookies;
use Utility\UHTTPMethods;
use Utility\UServer;
use Utility\USessions;
use DateTime;
use Entity\EUser;
use Entity\Role;
use Foundation\FUser;
use View\VUser;
use Foundation\FPersistentManager;
use Exception;

/**
 * Classe UserController
 *
 * Gestisce le azioni relative agli utenti, come la registrazione, il login e la gestione del profilo.
 */
class CUser {
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Function to verify if a user is logged
     * 
     * @return bool
     */
    static function isLogged() {
        $identifier=false;
        $session=USessions::getIstance();
        //Controllo se esiste il cookie PHPSESSID
        if($session->isSessionSet()) {
            if($session->isSessionNone()) {
                $session->startSession();
            }
        }
        //Controllo se l'utente è nella sessione dal suo id
        if($session->isValueSet('idUser')) {
            $identifier=true; //Utente loggato
        }
        return($identifier);
    }

    /**
     * Show Login/Register popup from the home page
     */
    public function showLoginRegister() {
        $view=new VUser();
        $view->showLoginRegisterPage();
    }

    /**
     * Show Profile/Logout popup from the home pag
     */
    public function showProfileLogout() {
        $view=new VUser();
        $view->showProfileLogoutPage();
    }

    /**
     * Show Login page after clicking "login" button in the popup menù
     */
    public function showLogin() {
        $view=new VUser();
        $view=showLoginPage();
    }

    /**
     * Show Register page after clicking "register" button in the popup menù
     */
    public function showRegister() {
        $view=new VUser();
        $view=showRegisterPage();
    }

    /**
     * Show logged user Profile
     */
    public function showProfile() {
        $session=USessions::getIstance();
        $session->startSession();
        $idUser=$session->readValue('idUser');
        $view=new VUser();
        //Carico l'utente da db per prelevare i suoi dati da visualizzare nel suo profilo
        $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
        $username=$user->getUsername();
        $email=$user->getEmail();
        $name=$user->getName();
        $username=$user->getSurname();
        $birthDate=$user->getBirthdate();
        $phone=$user->getPhone();
        $edit_section="";
        //Passo i parametri a view
        $view->showProfile($username, $email, $name, $username, $birthDate, $phone, $edit_section);
    }

    /**
     * Show the form for editing the data Profile
     */
    public function showEditProfileData() {
        
    }

    /**
     * Function to logout a user
     */
    public function logout() {
        $session=USessions::getIstance();
        $session->startSession();
        $session->stopSession();
        setcookie("PHPSESSID", "");
        header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');

    }

    /**
     * Function to validate user's data sended by the form and register the user
     */
    public function checkRegister() {
        $view=new VUser();
        $session=USessions::getIstance();
        //Creo un nuovo EUser con i dati provenienti dalla form HTML
        $newUser=new EUser(
            null,
            null,
            UHTTPMethods::post('username'),
            UHTTPMethods::post('email'),
            UHTTPMethods::post('password'),
            UHTTPMethods::post('image'),
            UHTTPMethods::post('name'),
            UHTTPMethods::post('surname'),
            new DateTime(UHTTPMethods::post('dateTime')),
            UHTTPMethods::post('phone'),
            Role::UTENTE,
            false
        );
        //Inserisco l'utente nel db (il controllo sulla validità dei campi è affidato a foundation)
        if(FPersistentManager::getInstance()->create($newUser)===null) {
            //Ci sono stati errori nell'inserimento a db, reindirizzo alla schermata home con errore
            $view->registerError();
        } else {
            //L'operazione ha avuto successo, reindirizzo alla schermata home e inserisco l'id dell'utente in sessione
            $session->startSession();
            $session->setValue('idUser', $newUser->getIdUser());
            header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');
        }
    }

    /**
     * Function to validate user's data sended by the form and log the user
     */
    public function checkLogin() {
    $view=new VUser();
    $session=USessions::getIstance();
    //Verifico se esiste un utente su db con stessa email e password inseriti nelle form HTML
    $checkUser=FPersistentManager::getInstance()->readUserByEmail(UHTTPMethods::post('email'), FUser::class);
    $checkEmail=$checkUser->getEmail();
    $checkPassword=$checkUser->getPassword();
    //Controllo effettivo
    if($checkEmail===UHTTPMethods::post('email') && $checkPassword===UHTTPMethods::post('password')) {
        //Controllo superato, verifico se l'utente sia un admin o un utente normale
        if($checkUser->isAdmin()) {
            //Reindirizzo alla home page dell'admin e aggiungo in sessione l'utente
            $session->startSession();
            $session->setValue('idUser', $checkUser->getIdUser());
            $view->showAdminHomePage();
            }
        //Verifico se l'utente è bannato
        if($checkUser->getBan()===1) {
            //Utente bannato
            $view->showLoginError();
            }
        }
    //Tutti i controlli passati, reindirizzo alla home page da loggato e inserisco in sessione
    $session->startSession();
    $session->setValue('idUser', $checkUser->getIdUser());
    header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');
    }

}