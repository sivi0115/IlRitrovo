<?php

namespace Foundation;

use DateTime;
use Entity\EUser;
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

    /**
     * Returns the name of the table associated with users.
     *
     * @return string The name of the table.
     */
    public function getTableName(): string {
        return static::TABLE_NAME;
    }

    /**
     * Converts an EUser object into an associative array for the database.
     *
     * @param EUser $user The user object to convert.
     * @return array The user data as an array.
     */
    private function userToArray(EUser $user): array {
        return [
            'idUser' => $user->getIdUser(),
            'idReview' => $user->getIdReview(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'image' => $user->getImage(),
            'ban' => $user->getBan(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'birthDate' => $user->getBirthDate(),
            'phone' => $user->getPhone(),
            'role' => $user->getRole(),
        ];
    }

    /**
     * Creates an EUser entity from the provided data.
     *
     * @param array $data The data used to create the EUser object.
     * @return EUser The created user object.
     * @throws Exception If an error occurs during the creation of the entity.
     */
    private function createEntityUser(array $data):EUser {
        return new EUser(
            $data['idUser'],
            $data['idReview'],
            $data['username'],
            $data['email'],
            $data['password'],
            $data['image'],
            $data['ban'],
            $data['name'],
            $data['surname'],
            new DateTime($data['birthDate']),
            $data['phone'],
            $data['role'],
        );
    }

    /**
     * Creates a new user in the database.
     *
     * @param EUser $user The user object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     */
    public function create(EUser $user): int {
        try {
            $db = FDatabase::getInstance();
            //hash della password in fase di creazione
            $user->setPassword($this->hashPassword($user->getPassword()));
            $data = $this->userToArray($user);
            return $db->insert(static::TABLE_NAME, $data) ?: 0;
        } catch (Exception $e) {
            error_log("Errore durante la creazione dell'utente: " . $e->getMessage());
            return 0; // Or use a different error handling approach
        }
    }

    /**
     * Reads a user from the database by their ID.
     *
     * @param int $id The ID of the user to read.
     * @return EUser|null The user object if found, null otherwise.
     * @throws Exception
     */
    public function read(int $id): ?EUser {
        $db = FDatabase::getInstance();
        $result = $db->load(static::TABLE_NAME, 'idUser', $id);
        return $result ? $this->createEntityUser($result) : null;
    }

    /**
     * Updates an existing user in the database.
     *
     * @param EUser $user The user object to update.
     * @return bool True if the update was successful, False otherwise.
     */
    public function update(EUser $user): bool {
        $db = FDatabase::getInstance();
        $data = $this->userToArray($user);
        return $db->update(static::TABLE_NAME, $data, ['idUser' => $user->getIdUser()]);
    }

    /**
     * Deletes a user from the database by their ID.
     *
     * @param int $id The ID of the user to delete.
     * @return bool True if the deletion was successful, False otherwise.
     */
    public function delete(int $id): bool {
        $db = FDatabase::getInstance();
        return $db->delete(static::TABLE_NAME, ['idUser' => $id]);
    }

    /**
     * Checks if a user exists in the database based on a specific field.
     *
     * @param string $field The field to check (e.g., 'idUser', 'username').
     * @param mixed $value The value of the field.
     * @return bool True if it exists, False otherwise.
     */
    public static function exists(string $idUser): bool {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['idUser' => $idUser]);
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
        $result = $db->load(static::TABLE_NAME, 'username', $username);
        return $result ? $this->createEntityUser($result) : null;
    }

    /**
     * Reads all users from the database.
     *
     * @return EUser[] An array of EUser objects.
     */
    public function readAllUsers(): array {
        $db = FDatabase::getInstance();
        $results = $db->fetchAllFromTable(static::TABLE_NAME);
        return array_map([$this, 'createEntityUser'], $results);
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
        return array_map([$this, 'createEntityUser'], $results);
    }

    /**
     * Blocks a user in the database.
     *
     * @param int $idUser The ID of the user.
     * @param string $motivation The reason for the block.
     * @return bool True if the operation is successful, False otherwise.
     */
    public function banUser(int $idUser, string $motivation): bool {
        $db = FDatabase::getInstance();
        return $db->update(static::TABLE_NAME, ['ban' => 1, 'motivation' => $motivation], ['idUser' => $idUser]);
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
}