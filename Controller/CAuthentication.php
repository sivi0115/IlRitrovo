<?php
namespace None\EventHubNewMain\Controller;

require_once __DIR__ . '/../Entity/EUser.php';
require_once __DIR__ . '/../Entity/EOwner.php';
require_once __DIR__ . '/../Entity/EAdmin.php';
require_once __DIR__ . '/../Foundation/FUser.php';
require_once __DIR__ . '/../Foundation/FOwner.php';
require_once __DIR__ . '/../Foundation/FAdmin.php';

use Entity\EUser;
use Entity\EOwner;
use Entity\EAdmin;
use Foundation\FDatabase;
use Foundation\FUser;
use Foundation\FOwner;
use Foundation\FAdmin;
use Exception;
use http\Client\Request;
use Random\RandomException;

//credo convenga eliminarla


class CAuthentication
{
    public function register(Request $request): void
    {
        try {
            // 1. Validazione dei dati
            $this->validateRegistrationData($request);

            // 2. Creazione dell'utente
            $user = $this->createUserFromRequest($request);

            // 3. Salvataggio dell'utente nel database
            if ($this->saveUser($user)) {
                // 4. Invio risposta di successo
                http_response_code(200);
                echo json_encode(['message' => 'Registrazione effettuata con successo!']);
            } else {
                // Gestione dell'errore di salvataggio
                http_response_code(500);
                echo json_encode(['error' => 'Errore durante il salvataggio dell\'utente.']);
            }


        } catch (Exception $e) {
            // Gestione dell'errore
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function login(Request $request): void
    {
        try {
            // 1. Validazione dei dati di input
            $this->validateLoginData($request);

            // 2. Autenticazione dell'utente
            $user = $this->authenticateUser($request);

            // 3. Avvio la sessione
            $this->startSession($user);

            // 4. Invio risposta di successo
            http_response_code(200);
            echo json_encode(['message' => 'Login effettuato con successo!']);

        } catch (Exception $e) {
            // Gestione dell'errore
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function logout(Request $request): void
    {
        // 1. Distruzione della sessione
        session_destroy();

        // 2. Invio risposta di successo
        http_response_code(200);
        echo json_encode(['message' => 'Logout effettuato con successo!']);
    }

    /**
     * @throws RandomException
     */
    private function generateRandomPassword(): string
    {
        $characters = '0123456789#abcdefghilkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+=-';
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $password;
    }

    /**
     * @throws Exception
     */
    private function updateUserPassword(string $email, string $newPassword): void
    {
        $fUser = new FUser(FDatabase::getInstance());
        $user = $fUser->loadByUsername($email);
        if ($user) {
            $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
            $fUser->update($user, $user->getIdUser()); // Modificato getId() in getIdUser()
            return;
        }

        $fOwner = new FOwner(FDatabase::getInstance());
        $owner = $fOwner->loadByUsername($email);
        if ($owner) {
            $owner->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
            $fOwner->update($owner, $owner->getIdOwner()); // Modificato getId() in getIdOwner()
            return;
        }

        $fAdmin = new FAdmin(FDatabase::getInstance());
        $admin = $fAdmin->loadByUsername($email);
        if ($admin) {
            $admin->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
            $fAdmin->update($admin, $admin->getIdAdmin()); // Modificato getId() in getIdAdmin()
            return;
        }

        throw new Exception('Email non associata a nessun account.');
    }

    public function resetPassword(Request $request): void
    {
        try {
            // 1. Validazione dell'email
            $this->validateEmail($request);

            // 2. Generazione di una nuova password casuale
            $newPassword = $this->generateRandomPassword();

            // 3. Aggiornamento della password dell'utente
            $this->updateUserPassword($_POST['email'], $newPassword);

            // 4. Invio risposta di successo
            http_response_code(200);
            echo json_encode(['message' => "La tua password è stata reimpostata. La nuova password è: $newPassword"]);
        } catch (Exception $e) {
            // Gestione dell'errore
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * @throws Exception
     */
    private function validateRegistrationData(Request $request): void
    {
        // Validazione dei dati
        if (
            empty($_POST['name']) ||
            empty($_POST['surname']) ||
            empty($_POST['username']) ||
            empty($_POST['email']) ||
            empty($_POST['password']) ||
            empty($_POST['phone']) ||
            empty($_POST['birthDate']) ||
            empty($_POST['userType'])
        ) {
            throw new Exception('Tutti i campi sono obbligatori.');
        }

        // Validazione del tipo di utente
        if (!in_array($_POST['userType'], ['user', 'owner', 'admin'])) {
            throw new Exception('Tipo di utente non valido.');
        }

        // Validazione della password
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            throw new Exception('La password deve essere di almeno 8 caratteri.');
        }
        // Aggiungi altre validazioni per la password se necessario

        // Validazione dell'email
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email non valida.');
        }
        // Verifica che l'username non sia già utilizzato
        $username = $_POST['username'];
        $fUser = new FUser();
        if ($fUser->loadByUsername($username)) {
            throw new Exception('Username già utilizzato.');
        }
        $fOwner = new FOwner();
        if ($fOwner->loadByUsername($username)) {
            throw new Exception('Username già utilizzato.');
        }
        $fAdmin = new FAdmin(FDatabase::getInstance());
        if ($fAdmin->loadByUsername($username)) {
            throw new Exception('Username già utilizzato.');
        }
        // Validazione del numero di telefono
        $phone = $_POST['phone'];
        if (!preg_match('/^[0-9]{10}+$/', $phone)) {
            throw new Exception('Numero di telefono non valido.');
        }

        // Validazione della data di nascita
        $birthDate = $_POST['birthDate'];
        $date = \DateTime::createFromFormat('Y-m-d', $birthDate);
        if (!$date || $date->format('Y-m-d') !== $birthDate) {
            throw new Exception('Data di nascita non valida.');
        }
    }

    /**
     * @throws Exception
     */
    private function createUserFromRequest(Request $request): EUser|EOwner|EAdmin
    {
        // Estrazione dei dati dalla richiesta
        $userType = $_POST['userType'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password']; // Password inserita dall'utente
        $phone = $_POST['phone'];
        $birthDate = new \DateTime($_POST['birthDate']);
        $image = $_FILES['image']['tmp_name']; // Immagine caricata dall'utente

        // Generazione di un nuovo ID univoco
        $newId = $this->generateUniqueId();

        // Creazione dell'oggetto Entity in base al tipo di utente
        if ($userType === 'user') {
            $user = new EUser(
                $newId,
                $username,
                $name,
                $surname,
                $birthDate,
                $phone,
                $image,
                $email,
                password_hash($password, PASSWORD_DEFAULT), // Hashing della password
                false,
                ""
            );
            $fUser = new FUser(FDatabase::getInstance());
            $fUser->save($user);
            return $user;

        } elseif ($userType === 'owner') {
            $owner = new EOwner(
                $newId,
                $username,
                $name,
                $surname,
                $birthDate,
                $phone,
                $image,
                $email,
                password_hash($password, PASSWORD_DEFAULT), // Hashing della password
                false
            );
            $fOwner = new FOwner(FDatabase::getInstance());
            $fOwner->save($owner);
            return $owner;

        } elseif ($userType === 'admin') {
            $admin = new EAdmin(
                $newId,
                $username,
                $name,
                $surname,
                $birthDate,
                $phone,
                $image,
                $email,
                password_hash($password, PASSWORD_DEFAULT), // Hashing della password
                null
            );
            $fAdmin = new FAdmin(FDatabase::getInstance());
            $fAdmin->save($admin);
            return $admin;

        } else {
            throw new Exception('Tipo di utente non valido.');
        }
    }

    // Funzione per generare un ID univoco
    /**
     * @throws RandomException
     * @throws Exception
     */
    private function generateUniqueId(): int
    {
        $fDatabase = FDatabase::getInstance();
        // Ottieni tutti gli ID già presenti nel database
        $ids = [];
        $users = FUser::loadAllUsers();
        foreach ($users as $user) {
            $ids[] = $user->getIdUser();
        }
        $owners = FOwner::loadAllOwners();
        foreach ($owners as $owner) {
            $ids[] = $owner->getIdOwner();
        }
        // Genera un nuovo ID finché non ne trovi uno univoco
        do {
            $newId = random_int(1, PHP_INT_MAX);
        } while (in_array($newId, $ids));
        return $newId;
    }

    /**
     * @throws Exception
     */
    private function saveUser($user): bool
    {
        // Salvataggio dell'utente nel database
        if ($user instanceof EUser) {
            $fUser = new FUser(FDatabase::getInstance());
            return $fUser->save($user) !== 0;
        } elseif ($user instanceof EOwner) {
            $fOwner = new FOwner(FDatabase::getInstance());
            return $fOwner->save($user) !== 0;
        } elseif ($user instanceof EAdmin) {
            $fAdmin = new FAdmin(FDatabase::getInstance());
            return $fAdmin->save($user) !== 0;
        } else {
            throw new Exception('Tipo di utente non valido.');
        }
    }

    /**
     * @throws Exception
     */
    private function validateLoginData(Request $request):void
    {
        // Validazione dei dati
        if (empty($_POST['username']) || empty($_POST['password'])) {
            throw new Exception('Username e password sono obbligatori.');
        }
    }

    /**
     * @throws Exception
     */
    private function authenticateUser(Request $request): EUser|EOwner|EAdmin
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Verifica delle credenziali utilizzando le classi Foundation
        $fUser = new FUser(FDatabase::getInstance());
        $user = $fUser->loadByUsername($username);

        if ($user && password_verify($password, $user->getPassword())) {
            // Utente autenticato
            return $user;
        }

        $fOwner = new FOwner(FDatabase::getInstance());
        $owner = $fOwner->loadByUsername($username);

        if ($owner && password_verify($password, $owner->getPassword())) {
            // Gestore autenticato
            return $owner;
        }

        $fAdmin = new FAdmin(FDatabase::getInstance());
        $admin = $fAdmin->loadByUsername($username);

        if ($admin && password_verify($password, $admin->getPassword())) {
            // Amministratore autenticato
            return $admin;
        }
        throw new Exception('Credenziali non valide.');
    }

    /**
     * @throws Exception
     */
    private function getUserType($user): string
    {
        if ($user instanceof EUser) {
            return 'user';
        } elseif ($user instanceof EOwner) {
            return 'owner';
        } elseif ($user instanceof EAdmin) {
            return 'admin';
        } else {
            throw new Exception('Tipo di utente non valido.');
        }
    }

    /**
     * @throws Exception
     */
    private function startSession($user): void
    {
        // Avvio la sessione
        session_start();
        // Variabili di sessione
        if ($user instanceof EUser) {
            $_SESSION['user_id'] = $user->getIdUser();
            $_SESSION['user_role'] = 'user';
        } elseif ($user instanceof EOwner) {
            $_SESSION['user_id'] = $user->getIdOwner();
            $_SESSION['user_role'] = 'owner';
        } elseif ($user instanceof EAdmin) {
            $_SESSION['user_id'] = $user->getIdAdmin();
            $_SESSION['user_role'] = 'admin';
        }
    }

    /**
     * @throws Exception
     */
    private function validateEmail(Request $request): void
    {
        // Validazione dell'email
        if (empty($_POST['email'])) {
            throw new Exception('L\'email è obbligatoria.');
        }

        // Verifica che l'email sia associata a un utente esistente
        $fUser = new FUser(FDatabase::getInstance());
        $user = $fUser->loadByUsername($_POST['email']);

        if ($user) {
            return;
        }

        $fOwner = new FOwner(FDatabase::getInstance());
        $owner = $fOwner->loadByUsername($_POST['email']);

        if ($owner) {
            return;
        }

        $fAdmin = new FAdmin(FDatabase::getInstance());
        $admin = $fAdmin->loadByUsername($_POST['email']);

        if ($admin) {
            return;
        }

        // Se l'email non è associata a nessun account
        throw new Exception('Email non associata a nessun account.');
    }
    // Metodo per verificare i permessi
    private function checkPermission(string $role): bool
    {
        // Definizione dei permessi per ciascun ruolo
        $permissions = [
            'user' => ['view_profile', 'make_reservation', 'cancel_reservation', 'add_credit_card', 'add_review'],
            'owner' => ['view_profile', 'add_room', 'remove_room', 'manage_calendar', 'reply_to_reviews'],
            'admin' => ['ban_user', 'reply_to_tickets']
        ];
        // Verifica se il ruolo ha il permesso specificato
        return isset($permissions[$role]) && in_array('view_profile', $permissions[$role]);
    }
    /**
     * @throws Exception
     */
    public function viewProfile(Request $request): void
    {
        session_start(); // Avvia la sessione per accedere ai dati dell'utente
        if (isset($_SESSION['user_role']) && $this->checkPermission($_SESSION['user_role'], 'view_profile')) {
            $userId = $_SESSION['user_id'];
            try {
                // Caricamento dei dati dell'utente dal database usando FDatabase
                $db = FDatabase::getInstance();
                if ($_SESSION['user_role'] === 'user') {
                    $tableName = FUser::TABLE_NAME;
                    $idField = 'idUser';
                } elseif ($_SESSION['user_role'] === 'owner') {
                    $tableName = FOwner::TABLE_NAME;
                    $idField = 'idOwner';
                } elseif ($_SESSION['user_role'] === 'admin') {
                    $tableName = FAdmin::TABLE_NAME;
                    $idField = 'idAdmin';
                } else {
                    // Gestione dell'errore: tipo di utente non valido
                    http_response_code(500);
                    echo json_encode(['error' => 'Tipo di utente non valido.']);
                    return;
                }
                $query = "SELECT * FROM $tableName WHERE $idField = :userId";
                $result = $db->fetchSingle($query, [':userId' => $userId]);
                if (!$result) {
                    // Gestione dell'errore: utente non trovato
                    http_response_code(404);
                    echo json_encode(['error' => 'Utente non trovato.']);
                    return;
                }
                // Preparazione dei dati del profilo
                $profileData = [
                    'id' => $result[$idField],
                    'username' => $result['username'],
                    'name' => $result['name'],
                    'surname' => $result['surname'],
                    'email' => $result['email'],
                    'phone' => $result['phone'],
                    'birthDate' => (new \DateTime($result['birthDate']))->format('Y-m-d'),
                ];
                // Aggiunta di informazioni specifiche per il tipo di utente
                if ($_SESSION['user_role'] === 'owner') {
                    $profileData['companyName'] = $result['companyName'];
                    $profileData['validation'] = $result['validation'];
                } elseif ($_SESSION['user_role'] === 'user') {
                    $profileData['ban'] = $result['ban'];
                    $profileData['motivation'] = $result['motivation'];
                } elseif ($_SESSION['user_role'] === 'admin') {
                    $profileData['mansion'] = $result['mansion'];
                }
                // Invio risposta di successo con i dati del profilo
                http_response_code(200);
                echo json_encode(['profile' => $profileData]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Errore durante il caricamento del profilo utente. ' . $e->getMessage()]);
            }

        } else {
            // Gestione dell'errore di autorizzazione
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'Non hai i permessi per visualizzare il profilo.']);
        }
    }
}