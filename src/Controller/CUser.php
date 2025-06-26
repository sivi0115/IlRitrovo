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
use Foundation\FReview;

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
     * Function to show login page with forms
     */
    public function showLoginForm() {
        $view=new VUser();
        $view->showLoginForm();
    }

    /**
     * Function to show signup page with forms
     */
    public function showSignUpForm() {
        $view=new VUser();
        $view->showSignUpForm();
    }

    /**
     * Function used to logout the user and redirect to home page
     */
    public function logout() {
        $session=USessions::getIstance();
        $session->startSession();
        $session->stopSession();
        setcookie("PHPSESSID", "");
        header("Location: /IlRitrovo/public/User/showHomePage");
        exit;
    }
    

    /**
     * Show logged user's Profile
     */
    public function showProfile() {
        $view=new VUser();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
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
        //Carico tutte le prenotazioni passate associate a quest'utente
        $userPastReservations=FPersistentManager::getInstance()->readPastReservationsByUserId($idUser, FReservation::class);
        //Carico tutte le prenotazioni future associate a quest'utente
        $userFutureReservations=FPersistentManager::getInstance()->readFutureReservationsByUserId($idUser, FReservation::class);
        //Carico le recensioni di questo utente
        $userReview=FPersistentManager::getInstance()->readReviewByUserId($idUser, FReview::class);
        //Passo i parametri a view
        $view->showUserHeader($isLogged);
        $view->showProfile($username, $email, $name, $surname, $birthDate, $phone, $edit_section, $userCreditCards, $userPastReservations, $userFutureReservations, $userReview);
    }

    /**
     * Show the form to edit the data Profile
     */
    public function showEditProfileData() {
        $view=new VUser();
        $view->showEditProfileData();
    }

    /**
     * Used to edit user's personal data
     */
    public function editProfileData() {
        $view=new VUser();
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
                header("Location: /IlRitrovo/public/User/showProfile");
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
        $view=new VUser();
        $view->showEditProfileMetadata();
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
                header("Location: /IlRitrovo/public/User/showProfile");
            } 
        } catch (Exception $e) {
            //Se ci sono stati errori reindirizzo ad una schermata di errore
            //$view->showError();
            echo "Errore" . $e->getMessage();
        }
    }

    

    /**
     * Function to validate user's data sent by the form and to redirect the user to the home page or to the error page.
     * A new user will be added in the database if registration was successful
     * 
     * @throws Excpetion if something goes wrong like existing username ecc
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
            UHTTPMethods::post('name'),
            UHTTPMethods::post('surname'),
            new DateTime(UHTTPMethods::post('birthDate')),
            UHTTPMethods::post('phone'),
            Role::UTENTE,
            false
        );
        //Inserisco l'utente nel db se tutto va buon fine altrimenti mostro errore
        try {
            FPersistentManager::getInstance()->create($newUser);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        $session->startSession();
        $session->setValue('idUser', $newUser->getIdUser());
        header("Location: /IlRitrovo/public/User/showHomePage");
        exit;
    }

    /**
     * Function to validate user's data sent by the form and to redirect the user to the home page or to the error page.
     * If register, the user will be logged.
     */
    public function checkLogin() {
    $view=new VUser();
    $session=USessions::getIstance();
    //Verifico se esiste un utente su db con stessa email e password inseriti nelle form HTML
    try {
        $checkUser=FPersistentManager::getInstance()->readUserByEmail(UHTTPMethods::post('email'), FUser::class);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
    //Controllo effettivo
    $checkEmail=$checkUser->getEmail();
    $checkPassword=$checkUser->getPassword();
    if($checkEmail===UHTTPMethods::post('email') && password_verify(UHTTPMethods::post('password'), $checkPassword)) {
        //Controllo superato, verifico se l'utente sia un admin o un utente normale
        if($checkUser->isAdmin()) {
            //Reindirizzo alla home page dell'admin e aggiungo in sessione l'utente
            $session->startSession();
            $session->setValue('idUser', $checkUser->getIdUser());
            //$view->showAdminHomePage();
            }
        //Verifico se l'utente è bannato
        if($checkUser->getBan()===1) {
            echo "Sei stato bannato";
            exit;
            }
        } else {
            echo "Password Errata, ricontrolla la password";
            exit;
        }
    //Tutti i controlli passati, reindirizzo alla home page da loggato e inserisco in sessione
    $session->startSession();
    $session->setValue('idUser', $checkUser->getIdUser());
    print_r($_SESSION);
    header("Location: /IlRitrovo/public/User/showHomePage");
    exit;
    }

    /**
     * Function to show home Page if user is logged or if is admin
     */
    public static function showHomePage() {
        $view=new VUser();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
            //Carico l'utente da db
            $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
            //Controllo che sia amministratore o utente normale, se è utente normale
            if($user->isUser() && $user->getBan()===0) {
                //Mostro l'header con l'informazione che l'utente è loggato
                $view->showUserHeader($isLogged);
                //Carico la home page correttamente per l'utente loggato
                $view->showLoggedUserHomePage($isLogged);
            }
            //Se è un admin lo reindirizzo alla sua home page
            elseif($user->isAdmin()) {
                //Carico l'header con l'informazione che l'admin è loggato
                $view->showAdminHeader($isLogged);
                //Carico la home page correttamente
                $view->showLoggedAdminHomePage($isLogged);
            }
        } else {
            //Mostro l'header con l'informazione che l'utente non è loggato
            $view->showUserHeader($isLogged);
            //Carico la home page correttamente per l'utente non loggato
            $view->showUserHomePage($isLogged);
        }
    }

    /**
     * Function to show Menù page
     */
    public function showMenuPage() {
        $view=new VUser();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $view->showUserHeader($isLogged);
        $view->showMenuPage();
    }

    /**
     * Function to show Rooms page
     */
    public function showRoomsPage() {
        $view=new VUser();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $view->showUserHeader($isLogged);
        $view->showRoomsPage();
    }

    /**
     * 
     */
    public function showUsersPage() {
        $view = new VUser();
        $session = USessions::getIstance();
        if ($isLogged = CUser::isLogged()) {
            $idUser = $session->readValue('idUser');
        }
        // Carico tutti gli utenti
        $allUsersRaw = FPersistentManager::getInstance()->readAll(FUser::class);
        // Separazione utenti bannati e non bannati (escludendo l'admin)
        $allUsers = [];
        $blocked_user = [];
        foreach ($allUsersRaw as $user) {
            if ($user->isAdmin()) {
                continue; // salto l'admin
            }
            if ($user->getBan() === 1) {
                $blocked_user[] = $user;
            } else {
                $allUsers[] = $user;
            }
        }
        $view->showAdminHeader($isLogged);
        $view->showUsersPage($blocked_user, $allUsers);
    }

    /**
     * Function to ban a User
     */
    public function banUser() {
        //Mi pappo l'id dell'utente da bannare
        $idUserToBan=UHTTPMethods::post('userId');
        //Carico da db l'oggetto entity
        $userToBan=FPersistentManager::getInstance()->read($idUserToBan, FUser::class);
        //Banno l'utente
        $userToBan->setBan(true);
        //Aggiorno lo stato dell'oggetto su db
        $bannedUser=FPersistentManager::getInstance()->update($userToBan);
        if($bannedUser) {
            header("Location: /IlRitrovo/public/User/showUsersPage");
        }

    }

    /**
     * Function to unban a User
     */
    public function unbanUser() {
        //Mi pappo l'id dell'utente da sbannare
        $idUserToUnban=UHTTPMethods::post('userId');
        //Carico da db l'oggetto entity
        $userToUnban=FPersistentManager::getInstance()->read($idUserToUnban, FUser::class);
        //Sbanno l'utente
        $userToUnban->setBan(false);
        //Aggiorno lo stato dell'oggetto su db
        $unbannedUser=FPersistentManager::getInstance()->update($userToUnban);
        if($unbannedUser) {
            header("Location: /IlRitrovo/public/User/showUsersPage");
        }
    }
}