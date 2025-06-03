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

   public static function isLogged(): bool {
    // Variabile di supporto
    $identificato = false;
    // Se non c'è una sessione attiva, la attivo (PHP controllerà PHPSESSID da solo)
    if (session_status() === PHP_SESSION_NONE) {
        USessions::getInstance();
        var_dump($identificato); // Chiama session_start()
    }
    // Verifico se l'id dell'utente è presente nella sessione
    if (USessions::isSetSessionElement('idUser')) {
        $identificato = true;
        // Eventuale controllo aggiuntivo: utente bannato?
        // (Se implementato)
    }
    // Se non è loggato, faccio redirect alla pagina di login
    if (!$identificato) {
         // Personalizza il path
        var_dump($identificato);
        exit;
    }
        // Altrimenti restituisco true (utente autenticato)
        var_dump($identificato);
        return $identificato;
    }

    /**
     * Function for login the user
     */
    public function login() {
        USessions::getInstance();
        $pm=FPersistentManager::getInstance();
        //Recupero dei dati dalla richiesta POST HTTP
        $email=UHTTPMethods::post('email');
        $password=UHTTPMethods::post('password');
        if(empty($email)||empty($password)) {
            Usessions::setSessionElement('error_message','Inserire email e password');
            header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_error_signup.html');
        }
        try {
            //Controllo dell'email
            $user=$pm->readUserByEmail($email, FUser::class);
            if(!$user instanceof EUser) {
                USessions::setSessionElement('error_message', 'Email o password invalidi');
                header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_error_signup.html');
                exit;
            }
            //Controllo della password
            if(!password_verify($password, $user->getPassword())) {
                USessions::setSessionElement('error_message', 'Email o password invalidi');
                header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_error_signup.html');
                exit;
            }
            if($user->getBan()===1) {
                USessions::setSessionElement('error_message', 'Il tuo account è stato sospeso');
                header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_error_signup.html');
                exit;
            }
            USessions::setSessionElement('idUser', $user);
            if($user->getRole()==='user') {
                header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');
                exit;
            } else {
                header('Pagina Admin');
                exit;
            }
        } catch (Exception $e) {
            USessions::setSessionElement('error_message', 'Si è verificato un errore');
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
    try {
        // Estrazione dati dalla POST e creazione dell'oggetto EUser
        $user = new EUser(
            null,
            null,
            UHTTPMethods::post('username'),
            UHTTPMethods::post('email'),
            UHTTPMethods::post('password'),
            null,
            UHTTPMethods::post('name'),
            UHTTPMethods::post('surname'),
            new DateTime(UHTTPMethods::post('birthDate')),
            UHTTPMethods::post('phone'),
            Role::UTENTE,
            false
        );
        // Inserimento nel DB
        $user = FPersistentManager::getInstance()->create($user);
        // Salvataggio in sessione
        USessions::getInstance()->setSessionElement('idUser', $user);
        // Reindirizzamento alla pagina di successo
        header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');
    } catch (Exception $e) {
        // Reindirizzamento in caso di errore
        header('Pagina di errore login');
        exit();
        }
    }

    /**
     * Funzione per modificare i dati profilo di un utente
     */
    public function editProfileData() {
        // Verifica che l'utente sia loggato
        if (!self::isLogged()) {
            header('Location: /IlRitrovo/Login');
            exit;
        }
        // Recupera l'id dell'utente dalla sessione
        $idUser = USessions::getSessionElement('idUser');
        // Recupera l'oggetto utente completo dal DB
        $user = FPersistentManager::getInstance()->read($idUser, FUser::class);
        if (!$user) {
            // Utente non trovato, potresti loggare un errore o reindirizzare
            header('Location: /IlRitrovo/Error');
            exit;
        }
        // Imposta i nuovi valori dai dati POST
        $user->setName(UHTTPMethods::post('name'));
        $user->setSurname(UHTTPMethods::post('surname'));
        $user->setBirthDate(new Datetime(UHTTPMethods::post('birthDate')));
        $user->setPhone(UHTTPMethods::post('phone'));
        //Alcuni controlli prima di aggionrare sulla validità dei campi (già fatta anche il HTML)
        if (empty($user->getName())) {
            throw new Exception("Name can't be empy");
        }
        if (empty($user->getSurname())) {
            throw new Exception("Surname can't be empty");
        }
        if (empty($user->getBirthDate())) {
            throw new Exception("Birth Date can't be empty.");
        }
        $now = new DateTime();
        if ($user->getBirthDate() > $now) {
            throw new Exception("Birth Date can't be in the future.");
        }
        if (empty($user->getPhone())) {
            throw new Exception("Phone number field can't be empty.");
        }
        $phone = $user->getPhone();
        if (!preg_match('/^\+?\d{8,15}$/', $phone)) {
            throw new Exception("Phone number must contain only digits and be between 8 and 15 characters.");
        }
        // Aggiorna l'utente nel DB
        FPersistentManager::getInstance()->updateProfileData($user);
        // Reindirizza alla pagina profilo o conferma
        header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');
        exit;
    }

    /**
     * Funzione per modificare l'username email e password
     */
    public function editProfileMetadata() {
        if(!self::isLogged()) {
            header('Location: /IlRitrovo/HomePage');
        }
        //Recupera id Utente dalla sessione
        $idUser=USessions::getSessionElement('idUser');
        //Recupera l'oggetto completo dal db per poter modificare
        $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
        //In caso di utente non trovato
        if(!$user) {
            header('Location: /IlRitrovo/HomePage');
            exit;
        }
        //Imposto il nuovo username dal valore della richiesta post
        $user->setUsername(UHTTPMethods::post('username'));
        $user->setEmail(UHTTPMethods::post('email'));
        $user->setPassword(UHTTPMethods::post('password'));
        //Controllo che i metadati immessi siano validi (sarà fatto anche lato client da HTML)
        if(empty($user->getUsername())) {
            throw new Exception("New Username field can't be empty");
        }
        if(empty($user->getEmail())) {
            throw new Exception("New Emial field can't be empty");
        }
        if(empty($user->getPassword())) {
            throw new Exception("New Password field can't be empty");
        }
        //Se è tutto okay, aggiorno il campo su db: Modifica effettuata con successo
        FPersistentManager::getInstance()->updateMetadataProfile($user);
        //Reindirizza alla pagina home
        header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');
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