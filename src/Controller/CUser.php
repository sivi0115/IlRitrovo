<?php

namespace Controller;
namespace Utility;

use DateTime;
use Entity\EUser;
use Entity\Role;
use Exception;
use Foundation\FUser;
use InvalidArgumentException;
use View\VUser;
use Utility\UCookies;
use Utility\USessions;
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
        $view = new VUser;
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
     * Gestisce la registrazione di un nuovo utente.
     * Se il metodo della richiesta è GET, mostra il form di registrazione.
     * Se il metodo della richiesta è POST, processa i dati del form e tenta di registrare l'utente.
     * @throws Exception
     */
    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $view = new VUser();
            $view->showRegistrationForm();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->registerUser();
        }
    }

    /**
     * Processa i dati del form di registrazione e tenta di registrare un nuovo utente.
     * Verifica se l'username è già in uso tramite `readUserByUsername()`.
     * Crea un nuovo oggetto EUser con i dati del form.
     * Utilizza FUser per salvare l'utente nel database.
     * Reindirizza l'utente alla pagina di login in caso di successo.
     * Mostra un messaggio di errore in caso di fallimento.
     * @throws Exception
     */
    private function registerUser(): void
    {
        $view = new VUser();
        $fUser = new FUser();
        // Verifica se l'utente esiste già
        if ($fUser->readUserByUsername($_POST['username'])) {
            $view->showRegistrationError("Username già esistente.");
            return;
        }

        try {
            $user = new EUser(
                null,
                $_POST['idReview'] ?? null,
                $_POST['username'],
                $_POST['email'],
                $_POST['password'],
                $_POST['image'] ?? null,
                $_POST['name'],
                $_POST['surname'],
                new DateTime($_POST['birthDate']),
                $_POST['phone'],
                Role::UTENTE,
                $_POST['ban']
            );
        } catch (InvalidArgumentException $e) {
            $view->showRegistrationError($e->getMessage()); // nella view si deve mettere il tipo di errore da mostrare
            return;
        }

        try {
            $fUser->create($user);
            header('Location: /user/login');
        } catch (Exception $e) {
            $view->showRegistrationError($e->getMessage());
        }
    }

    /**
     * Processa i dati del form di login e tenta di effettuare il login dell'utente.
     * Utilizza FUser per verificare le credenziali dell'utente.
     * Avvia la sessione e reindirizza l'utente alla homepage in caso di successo.
     * Mostra un messaggio di errore in caso di fallimento.
     */
    private function loginUser(): void
    {
        $view = new VUser();
        $fUser = new FUser();

        try {
            $user = $fUser->readUserByUsername($_POST['username']);
            if ($user && $user->getPassword() == $_POST['password']) {
                session_start();
                $_SESSION['user'] = serialize($user);
                header('Location: /');
            } else {
                $view->showLoginError("Credenziali non valide.");
            }
        } catch (Exception $e) {
            $view->showLoginError($e->getMessage());
        }
    }

    /**
     * Mostra il profilo dell'utente loggato.
     * Recupera l'utente dalla sessione.
     * Reindirizza alla pagina di login se l'utente non è loggato.
     * Mostra la view del profilo.
     */
    public function profile(): void
    {
        if ($this->isLogged()) {
            $user = unserialize($_SESSION['user']);
            $view = new VUser();

            // Ottieni la cronologia dei pagamenti dell'utente
            $cPayment = new CPayment();
            try {
                $payments = $cPayment->cronologiaPagamenti($user->getIdUser());
                $view->showProfile($user, $payments);
            } catch (Exception $e) {
                $view->showProfileError($e->getMessage());
            }
        } else {
            header('Location: /user/login');
        }
    }

    /**
     * Processa i dati del form di modifica del profilo e tenta di aggiornare il profilo utente.
     * Recupera l'utente dalla sessione.
     * Aggiorna i dati dell'oggetto EUser con i nuovi valori dal form.
     * Utilizza FUser per aggiornare l'utente nel database.
     * Aggiorna i dati dell'utente in sessione.
     * Reindirizza l'utente alla pagina del profilo in caso di successo.
     * Mostra un messaggio di errore in caso di fallimento.
     */
    private function updateProfile(): void
    {
        if ($this->isLogged()) {
            $view = new VUser();
            $fUser = new FUser();
            $user = unserialize($_SESSION['user']);

            try {
                // Aggiorna i dati dell'utente
                $user->setUsername($_POST['username']);
                $user->setEmail($_POST['email']);
                if (!empty($_POST['password'])) {
                    $user->setPassword($_POST['password']);
                }
                $user->setImage($_POST['image'] ?? null);
                $user->setName($_POST['name']);
                $user->setSurname($_POST['surname']);
                $user->setBirthDate(new DateTime($_POST['birthDate']));
                $user->setPhone($_POST['phone']);

                $fUser->update($user);
                $_SESSION['user'] = serialize($user);
                header('Location: /user/profile');
            } catch (InvalidArgumentException $e) {
                $view->showEditProfileError($e->getMessage());
                return;
            } catch (Exception $e) {
                $view->showEditProfileError($e->getMessage());
            }
        } else {
            header('Location: /user/login');
        }
    }

    /**
     * Gestisce la cancellazione del profilo utente.
     * Recupera l'utente dalla sessione.
     * Utilizza FUser per eliminare l'utente dal database.
     * Distrugge la sessione e reindirizza l'utente alla pagina di login in caso di successo.
     * Mostra un messaggio di errore in caso di fallimento.
     * Reindirizza alla pagina di login se l'utente non è loggato.
     */
    public function deleteProfile(): void
    {
        if ($this->isLogged()) {
            $view = new VUser();
            $fUser = new FUser();
            $user = unserialize($_SESSION['user']);

            try {
                $fUser->delete($user->getIdUser());
                session_destroy();
                header('Location: /user/login');
            } catch (Exception $e) {
                $view->showProfileError($e->getMessage());
            }
        } else {
            header('Location: /user/login');
        }
    }
}