<?php

namespace Controller;

use DateTime;
use Entity\EUser;
use Entity\Role;
use Exception;
use Foundation\FUser;
use InvalidArgumentException;
use View\VUser;

/**
 * Classe UserController
 *
 * Gestisce le azioni relative agli utenti, come la registrazione, il login e la gestione del profilo.
 */
class CUser
{
    /**
     * Verifica se l'utente è loggato.
     *
     * @return bool True se l'utente è loggato, false altrimenti.
     */
    public function isLogged(): bool {
        return isset($_SESSION['user']);
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
     * Gestisce il login dell'utente.
     * Se il metodo della richiesta è GET, mostra il form di login.
     * Se il metodo della richiesta è POST, processa i dati del form e tenta di effettuare il login.
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $view = new VUser();
            $view->showLoginForm();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->loginUser();
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
     * Gestisce la modifica del profilo utente.
     * Se il metodo della richiesta è GET, mostra il form di modifica del profilo.
     * Se il metodo della richiesta è POST, processa i dati del form e tenta di aggiornare il profilo.
     * Reindirizza alla pagina di login se l'utente non è loggato.
     */
    public function editProfile(): void
    {
        if ($this->isLogged()) {
            if ($_SERVER['REQUEST_METHOD'] == "GET") {
                $user = unserialize($_SESSION['user']);
                $view = new VUser();
                $view->showEditProfileForm($user);
            } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
                $this->updateProfile();
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

    /**
     * Gestisce il logout dell'utente.
     * Distrugge la sessione e reindirizza l'utente alla pagina di login.
     */
    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: /user/login');
    }
}