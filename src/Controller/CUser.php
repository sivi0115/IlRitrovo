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
use Foundation\FCreditCard;
use Foundation\FPayment;
use Foundation\FReservation;

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
     * Function to check if a user is logged
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
     * Show Login page after clicking the "login" button in the popup menu
     */
    public function showLogin() {
        $view=new VUser();
        $view=showLoginPage();
    }

    /**
     * Show Register page after clicking the "register" button in the popup menu
     */
    public function showRegister() {
        $view=new VUser();
        $view=showRegisterPage();
    }

    /**
     * Show logged user's Profile
     */
    public function showProfile() {
        $session=USessions::getIstance();
        $session->startSession();
        $idUser=$session->readValue('idUser');
        $view=new VUser();
        //Carico l'utente da db per prelevare i suoi dati da visualizzare nel suo profilo
        $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
        //Inserisco i dati da visualizzare nelle variabili che poi saranno passate a view
        $username=$user->getUsername();
        $email=$user->getEmail();
        $name=$user->getName();
        $surname=$user->getSurname();
        $birthDate=$user->getBirthdate();
        $phone=$user->getPhone();
        $edit_section="";
        //Carico tutte le carte di credito dell'utente
        $userCreditCards=FPersistentManager::getInstance()->readCreditCardsByUser($idUser, FCreditCard::class);
        //Carico tutte le prenotazioni associate a quest'utente
        $userReservations=FPersistentManager::getInstance()->readReservationsByUserId($idUser, FReservation::class);
        //Passo i parametri a view
        $view->showProfile($username, $email, $name, $surname, $birthDate, $phone, $edit_section, $userCreditCards, $userReservations);
    }

    /**
     * Show the form to edit the data Profile
     */
    public function showEditProfileData() {
        //Da discutere bene il funzionamento
    }

    /**
     * Used to edit user's personal data
     */
    public function editProfileData() {
        //$view=new VUser();
        $session=USessions::getIstance();
        $session->startSession();
        //Prendo l'id utente dalla sessione
        $idUser=$session->readValue('idUser');
        //Carico l'oggetto entity di questo utente dal db
        $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
        //Modifico i dati personali dell'oggetto con quelli inseriti nelle form HTML
        $user->setName(UHTTPMethods::post('name'));
        $user->setSurname(UHTTPMethods::post('surname'));
        $user->setBirthDate(new DateTime(UHTTPMethods::post('birthDate')));
        $user->setPhone(UHTTPMethods::post('phone'));
        //Aggiorno l'oggetto entity con questi nuovi valori. Metto il tutto in un blocco try catch che gestirà gli errori che provengono dalle validazioni foundation
        try {
            $updated=FPersistentManager::getInstance()->updateProfileData($user);
            //Verifico se ci sono errori
            if($updated) {
                //Nessun errore, reindirizzo alla home page
                header("Home Page");
            }
        } catch (Exception $e) {
            //Se ci sono stati errori reindirizzo ad una schermata di errore
            $view->showError();
            echo "Errore" . $e->getMessage();
        }
    }

    /**
     * Show the form to edit personal metadata
     */
    public function showEditProfileMetadata() {
        //Da definire
    }

    /**
     * Used to edit user's personal metadata 
     */
    public function editProfileMetadata() {
        $view=new VUser();
        $session=USessions::getIstance();
        $session->startSession();
        //Prendo l'id utente dalla sessione
        $idUser=$session->readValue('idUser');
        //Carico l'oggetto entity da DB di questo utente
        $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
        //Modifico i metadati dell'oggetto con quelli inseriti nella form HTML
        $user->setUsername(UHTTPMethods::post('username'));
        $user->setEmail(UHTTPMethods::post('email'));
        $user->setPassword(UHTTPMethods::post('password'));
        //Aggiorno l'oggetto entity con i nuovi metadati. Metto il tutto in un blocco try per la gestione degli errori 
        try {
            $updated=FPersistentManager::getInstance()->updateProfileMetadata($user);
            //Verifico se ci sono errori
            if($updated) {
                //Nessun errore, reindirizzo alla pagina home con dati modificati
                echo "Operazione avvenuta con successo";
            } 
        } catch (Exception $e) {
            //Se ci sono stati errori reindirizzo ad una schermata di errore
            //$view->showError();
            echo "Errore" . $e->getMessage();
        }
        
    }

    /**
     * Function used to logout the user
     */
    public function logout() {
        $session=USessions::getIstance();
        $session->startSession();
        $session->stopSession();
        setcookie("PHPSESSID", "");
        header('Location: /~marco/Progetto/IlRitrovo/test/testController/test_success_signup.html');

    }

    /**
     * Function to validate user's data sent by the form and to redirect the user to the home page or to the error page.
     * A new user will be added in the database if registration was successful
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
     * Function to validate user's data sent by the form and to redirect the user to the home page or to the error page.
     * If register, the user will be logged.
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