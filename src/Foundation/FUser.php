<?php
namespace Foundation;

use DateTime;
use Entity\EUser;
use Entity\Role;
use Exception;

/**
 * Class FUser
 * Handles CRUD operations for users in the database.
 */
class FUser {
    /**
     * Name of the table associated with the user entity in the database.
     */
    protected const TABLE_NAME = 'user';

    // Error messages centralized for consistency
    protected const ERR_MISSING_FIELD= 'Missing required field:';
    protected const ERR_USERNAME_TAKEN = 'Username is already taken.';
    protected const ERR_EMAIL_TAKEN = 'Email address is already in use.';
    protected const ERR_MISSING_ID= 'Unable to retrieve the ID of the inserted user';
    protected const ERR_INSERTION_FAILED = 'Error during the insertion of the user.';
    protected const ERR_RETRIVE_USER='Failed to retrive the inserted user.';
    protected const ERR_USER_NOT_FOUND = 'The user does not exist.';
    protected const  ERR_UPDATE_FAILED = 'Error during the update operation.';


    /**
     * Creates a new user in the database.
     *
     * @param EUser $user The user object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     * @throws Exception If there is an error during the create operation.
     */
    public function create(EUser $user): int {
        $db = FDatabase::getInstance();
        //hashing the password in the creation phase
        $user->setPassword($this->hashPassword($user->getPassword()));
        $data = $this->entityToArray($user);
        self::validateUserData($data);
        try {
            //User insertion
            $result = $db->insert(self::TABLE_NAME, $data);
            if ($result === null) {
                throw new Exception(self::ERR_INSERTION_FAILED);
            }
            //Retrive the last inserted ID
            $createdId=$db->getLastInsertedId();
            if ($createdId==null) {
                throw new Exception(self::ERR_MISSING_ID);
            }
            //Retrive the inserted user by number to get the assigned idUser
            $storedUser = $db->load(self::TABLE_NAME, 'idUser', $createdId);
            if ($storedUser === null) {
                throw new Exception(self::ERR_RETRIVE_USER);
            }
            //Assign the retrieved ID to the object
            $user->setIdUser((int)$createdId);
            //Return the id associated with this extra
            return (int)$createdId;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Reads a user from the database by their ID.
     *
     * @param int $id The ID of the user to read.
     * @return EUser|null The user object if found, null otherwise.
     */
    public function read(int $idUser): ?EUser {
        $db = FDatabase::getInstance();
        $result = $db->load(static::TABLE_NAME, 'idUser', $idUser);
        return $result ? $this->arrayToEntity($result) : null;
    }

    /**
     * Loads a user from the database by their username.
     *
     * @param string $username The username of the user to read.
     * @return EUser|null The user object if found, null otherwise.
     * @throws Exception
     */
    public function readUserByUsername(string $username): ?EUser {
        $db = FDatabase::getInstance();
        $result = $db->load(self::TABLE_NAME, 'username', $username);
        if (!$result) {
            return null;
        }
        // Temporary instance
        $tmp = new self();
        return $tmp->arrayToEntity($result);
    }

    /**
     * Updates an existing user in the database.
     *
     * @param EUser $user The user object to update.
     * @return bool True if the update was successful, False otherwise.
     * @throws Exception If there is an error during the update operation.
     */
    public function update(EUser $user): bool {
        $db = FDatabase::getInstance();
        if (!self::exists($user->getIdUser())) {
            throw new Exception(self::ERR_USER_NOT_FOUND);
        }
        //Hash della password prima di aggiornarla e caricarla
        $hashedPassword=$this->hashPassword($user->getPassword());
        $data = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $hashedPassword,
            'image' => $user->getImage(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'birthDate' => $user->getBirthDate(),
            'phone' => $user->getPhone(),
        ];
        // Checks for duplicates
        $duplicates = self::checkEmailAndUsernameDuplicate($data, $user->getIdUser());
        if ($duplicates['emailDuplicata']) {
            throw new Exception('Email already in use by another user.');
        }
        if ($duplicates['usernameDuplicato']) {
            throw new Exception('Username already in use by another user.');
        }
        if (!$db->update(self::TABLE_NAME, $data, ['idUser' => $user->getIdUser()])) {
            throw new Exception(self::ERR_UPDATE_FAILED);
        }
        return true;
    }

    /**
     * Deletes a user from the database by their ID.
     *
     * @param int $idUser The ID of the user to delete.
     * @return bool True if the deletion was successful, False otherwise.
     */
    public function delete(int $idUser): bool {
        $db = FDatabase::getInstance();
        return $db->delete(static::TABLE_NAME, ['idUser' => $idUser]);
    }

    /**
     * Reads all users from the database.
     *
     * @return EUser[] An array of EUser objects.
     */
    public function readAll(): array {
        $db = FDatabase::getInstance();
        $results = $db->fetchAllFromTable(static::TABLE_NAME);
        return array_map([$this, 'arrayToEntity'], $results);
    }

    /**
     * Returns a list of blocked users from the database.
     *
     * @return EUser[] An array of EUser objects representing the blocked users.
     * @throws Exception If an error occurs during the loading process.
     */
    public function readBlockedUsers(): array {
        $db = FDatabase::getInstance();
        $results = $db->fetchWhere(static::TABLE_NAME, ['ban' => 1]);
        return array_map([$this, 'arrayToEntity'], $results);
    }

    /**
     * Bans a user in the database, only if the requesting user is an admin
     * and the target user has the role of a normal user.
     *
     * @param EUser $admin The user attempting to perform the ban (must be an admin).
     * @param EUser $target The user to be banned (must be a regular user).
     * @return bool True if the operation was successful, false otherwise.
     */
    public function banUser(EUser $admin, EUser $target): bool {
        if ($admin->isAdmin() && $target->isUser()) {
            $db = FDatabase::getInstance();
            return $db->update(FUser::TABLE_NAME, ['ban' => 1], ['idUser' => $target->getIdUser()]);
        }
        return false;
    }

    /**
     * Unbans a user in the database.
     *
     * @param EUser $admin The admin performing the action.
     * @param EUser $target The user to be unbanned.
     * @return bool True if the operation was successful, false otherwise.
     */
    public function unbanUser(EUser $admin, EUser $target): bool {
        if ($admin->isAdmin() && $target->isUser()) {
            $db = FDatabase::getInstance();
            return $db->update(FUser::TABLE_NAME, ['ban' => 0], ['idUser' => $target->getIdUser()]);
        }
        return false;
    }

    /**
     * Checks if a user exists in the database by their ID.
     *
     * @param string $idUser The ID of the user to check.
     * @return bool True if the user exists, false otherwise.
     */
    public static function exists(int $idUser): bool {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['idUser' => $idUser]);
    }

    /**
     * Checks if a user exists in the database by username.
     *
     * @param string $username The username to check.
     * @return bool True if it exists, False otherwise.
     */
    public static function existsByUsername(string $username): bool {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['username' => $username]);
    }

    /**
     * Checks if a user exists in the database by email.
     *
     * @param string $email The email to check.
     * @return bool True if it exists, False otherwise.
     */
    public static function existsByEmail(string $email): bool {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['email' => $email]);
    }

    /**
     * Hashes the password using the bcrypt algorithm.
     *
     * @param string $password The plain-text password.
     * @return string The hashed password.
     */
    public function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Validates user input data that cannot be verified within the EUser entity.
     *
     * This function ensures the uniqueness of username and email only.
     * Other fields are validated by the entity during instantiation or via setters.
     *
     * @param array $data Associative array containing user input fields.
     * @throws Exception if any validation fails.
     */
    function validateUserData(array $data): void {
        // Check if the username is already in use
        if (self::existsByUsername($data['username'])) {
            throw new Exception(self::ERR_USERNAME_TAKEN);
        }
        // Check if the email is already in use
        if (self::existsByEmail($data['email'])) {
            throw new Exception(self::ERR_EMAIL_TAKEN);
        }
        // Other validations (e.g., password, birthDate, phone) are handled in the EUser entity.
    }

    /**
     * Checks for duplicate email and username entries in the database,
     * excluding the user with the specified ID.

     *
     * @param array $data An associative array containing 'email' and 'username' keys.
     * @param int $excludedIdUser The user ID to exclude from the duplication check.
     * @return array An associative array with boolean keys:
     *               - 'emailDuplicata': true if the email is duplicated, false otherwise.
     *               - 'usernameDuplicato': true if the username is duplicated, false otherwise.
     */
    function checkEmailAndUsernameDuplicate(array $data, int $excludedIdUser): array {
        $db = FDatabase::getInstance();
        // Query with condition OR and idUser different
        $users = $db->fetchAll('users', [
            'OR' => ['email' => $data['email'], 'username' => $data['username']],
            'idUser[!]' => $excludedIdUser
        ]);
        // Initialize flags
        $duplicateEmail = false;
        $duplicateUsername = false;
        // Checks for duplicates
        foreach ($users as $user) {
            if ($user['email'] === $data['email']) {
                $duplicateEmail = true;
            }
            if ($user['username'] === $data['username']) {
                $duplicateUsername = true;
            }
        }
        return [
            'duplicateEmail' => $duplicateEmail, 'duplicateUsername' => $duplicateUsername];
    }

    /**
     * Creates an EUser entity from the provided data.
     *
     * @param array $data The data used to create the EUser object.
     * @return EUser The created user object.
     * @throws Exception If required fields are missing.
     */
    private function arrayToEntity(array $data):EUser {
        $requiredFields = ['idUser', 'username', 'email', 'password', 'name', 'surname', 'birthDate', 'phone', 'role'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception(self::ERR_MISSING_FIELD . $field);
            }
        }
        return new EUser(
            $data['idUser'],
            $data['idReview'],
            $data['username'],
            $data['email'],
            $data['password'],
            $data['image'],
            $data['name'],
            $data['surname'],
            new DateTime($data['birthDate']),
            $data['phone'],
            isset($data['role']) ? Role::Tryfrom($data['role']) : null,
            $data['ban']
        );
    }

    /**
     * Converts an EUser object into an associative array for the database.
     *
     * @param EUser $user The user object to convert.
     * @return array The user data as an array.
     */
    private function entityToArray(EUser $user): array {
        return [
            'idUser' => $user->getIdUser(),
            'idReview' => $user->getIdReview(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'image' => $user->getImage(),
            'ban' => $user->getBan() ? 1 : 0,
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'birthDate' => $user->getBirthDate(),
            'phone' => $user->getPhone(),
            'role' => $user->getRole(),
        ];
    }
}