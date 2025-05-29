<?php

namespace Controller;

use Utility\UCookies;
use Utility\UHTTPMethods;
use Utility\UServer;
use Utility\USessions;
use DateTime;
use Entity\EUser;
use Foundation\FUser;
use View\VUser;
use Foundation\FPersistentManager;

/**
 * Classe UserController
 *
 * Gestisce le azioni relative agli utenti, come la registrazione, il login e la gestione del profilo.
 */
class CUser {

    public static function isLogged() {
    // Assicura la sessione
    if(session_status() == PHP_SESSION_NONE) {
        USessions::getInstance();
    }

    // Controlla se c’è idUser in sessione
    if(!USessions::isSetSessionElement('idUser')) {
        header('Location: /IlRitrovo/Home');
        exit;
    }

    // Qui eventualmente controlla se bannato
    $userId = USessions::getSessionElement('idUser');
    $user = FPersistentManager::getInstance()->read($userId, FUser::class);
    if ($user && $user->getBan()) {
        // Logout o redirect per utente bannato
        USessions::destroySession();
        header('Location: /IlRitrovo/Home?banned=1');
        exit;
        }
    return true;
    }

    /**
     * Function for login the user
     */
    public function login() {
        $view=new VUser;
        //Verifico se l'utente è già in sessione, in caso lo reindirizzo alla home, già loggato 
        if(USessions::getInstance()->isSetSessionElement('idUser')) {
            header('Location: /IlRitrovo/Home');
            exit;
        }
        //Recupero dei dati dalla richiesta POST HTTP
        $email=UHTTPMethods::post('email');
        $password=UHTTPMethods::post('password');

        //Recupero dell'oggetto dal db
        $user=FPersistentManager::getInstance()->loadEmail('email');
        if($user->getEmail() != $email) {
            $view->loginError("Utente Non Trovato");
            return;
        }
        if(!password_verify($password, $user->getPassword())) {
            $view->loginError("Password Errata");
            return;
        }
        if($user->getBan() == true) {
            $view->loginError("Utente Bannato");
            return;
        }
        
        //Check superati, login effettuato e utente inserito in sessione
        USessions::getInstance()->setSessionElement('idUser', $user->getIdUser());
        if($user->getRole() === 'admin') {
            header('Location: /IlRitrovo/AdminHome');
            exit;
        } else { 
            header('Location: /IlRitrovo/Home');
            exit;
        }
        
    }

    /**
     * Funzione per sloggare un utente
     */
    public function logout() {
        USessions::getInstance();
        USessions::unsetSession();
        USessions::destroySession();
        header('Location: /IlRitrovo/Home');
        exit;
    }

    /**
     * Funzione per registrare un utente
     */
    public function signup() {
        //$view = new VUser;
        //Estraggo i dati dalla richiesta POST per creare un oggetto Entity
        $user = new EUser(
            UHTTPMethods::post('idUser'),
            UHTTPMethods::post('idReview'),
            UHTTPMethods::post('username'),
            UHTTPMethods::post('email'),
            password_hash(UHTTPMethods::post('password'), PASSWORD_DEFAULT),
            UHTTPMethods::post('image'),
            UHTTPMethods::post('name'),
            UHTTPMethods::post('surname'),
            new DateTime(UHTTPMethods::post('birthDate')),
            UHTTPMethods::post('phone'),
            UHTTPMethods::post('role'),
            UHTTPMethods::post('ban')
        );
        $user=FPersistentManager::getInstance()->create($user);
        USessions::getInstance()->setSessionElement('idUser', $user);
        //Utente registrato correttamente, reindirizzo alla home page
        header('Location: /IlRitrovo/Home');
    }

    /**
     * Funzione per modificare i dati profilo di un utente
     */
    public function editProfile() {
    if (CUser::isLogged()) {
        $userId = USessions::getInstance()->getSessionElement('idUser');
        $user = FPersistentManager::getInstance()->read($userId, FUser::class);

        $user->setUsername(UHTTPMethods::post('username'));
        $user->setEmail(UHTTPMethods::post('email'));

        $newPassword = UHTTPMethods::post('password');
        if (!empty($newPassword)) {
            $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
        }

        $user->setImage(UHTTPMethods::post('image'));
        $user->setName(UHTTPMethods::post('name'));
        $user->setSurname(UHTTPMethods::post('surname'));
        $user->setBirthDate(UHTTPMethods::post('birthDate'));
        $user->setPhone(UHTTPMethods::post('phone'));

        FPersistentManager::getInstance()->update($user);

        header('Location: /IlRitrovo/Home');
        exit;
    } else {
        header('Location: /IlRitrovo/Home');
        exit;
        }
    }

    /**
     * Funzione per bannare un utente
     */
    public function bannUser() {
        $view=new VUser;
        if(!CUser::isLogged()) {
            header('Location: /IlRitrovo/Home');
            $view->loginError();
            exit;
        }
        $adminId=USessions::getInstance()->getSessionElement('idUser');
        $admin=FPersistentManager::getInstance()->read($adminId, FUser::class);
        //Controllo se sia effettivmente un admin per evitare attacchi URL
        if($admin->isAdmin()) {
            //prendo l'id dell'utente dalla richiesta post che l'admin ha inviato al server
            $idToBan=UHTTPMethods::post('idUser');
            $userToBan=FPersistentManager::getInstance()->read($idToBan, FUser::class);
            $admin->bannUser($adminId, $userToBan);
            header("Location: /IlRitrovo/UserList");
            exit;
        }
    }
}