<?php

namespace Foundation;

use DateTime;
use Entity\EUser;
use Exception;

/**
 * Class FUser
 * Gestisce le operazioni CRUD per gli utenti nel database.
 */
class FUser extends FPerson
{
    /**
     * Nome della tabella associata all'entità utente nel database.
     */
    protected const TABLE_NAME = 'user';

    /**
     * Restituisce il nome della tabella associata agli utenti.
     *
     * @return string Il nome della tabella.
     */
    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * Converte un oggetto EUser in un array associativo per il database.
     *
     * @param EUser $user L'oggetto utente da convertire.
     * @return array I dati dell'utente come array.
     */
    private function userToArray(EUser $user): array
    {
        $user->setPassword($this->hashPassword($user->getPassword()));

        return [
            'idUser' => $user->getIdUser(),
            'username' => $user->getUsername(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'birthDate' => $user->getBirthDate()->format('Y-m-d'),
            'phone' => $user->getPhone(),
            'image' => $user->getImage(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'ban' => $user->getBan(),
            'motivation' => $user->getMotivation(),
        ];
    }

    /**
     * Crea un'entità EUser a partire dai dati forniti.
     *
     * @param array $data I dati utilizzati per creare l'oggetto EUser.
     * @return EUser L'oggetto utente creato.
     * @throws Exception Se si verifica un errore durante la creazione dell'entità.
     */
    private function createEntityUser(array $data): EUser
    {
        return new EUser(
            $data['idUser'],
            $data['username'],
            $data['name'],
            $data['surname'],
            new DateTime($data['birthDate']),
            $data['phone'],
            $data['image'],
            $data['email'],
            $data['password'],
            $data['ban'],
            $data['motivation'] ?? '' // Usa stringa vuota se il campo è null
        );
    }

    /**
     * Crea un nuovo utente nel database.
     *
     * @param EUser $user L'oggetto utente da salvare.
     * @return int L'ID del record salvato o 0 se il salvataggio fallisce.
     */
    public function createUser(EUser $user): int
    {
        try {
            $db = FDatabase::getInstance();
            $data = $this->userToArray($user);
            return $db->insert(static::TABLE_NAME, $data) ?: 0;
        } catch (Exception $e) {
            error_log("Errore durante la creazione dell'utente: " . $e->getMessage());
            return 0; // O gestisci diversamente l'errore
        }
    }

    /**
     * Legge un utente dal database dato il suo ID.
     *
     * @param int $id L'ID dell'utente da leggere.
     * @return EUser|null L'oggetto utente se trovato, null altrimenti.
     * @throws Exception
     */
    public function readUser(int $id): ?EUser
    {
        $db = FDatabase::getInstance();
        $result = $db->load(static::TABLE_NAME, 'idUser', $id);
        return $result ? $this->createEntityUser($result) : null;
    }

    /**
     * Verifica se un utente esiste nel database dato un campo specifico.
     *
     * @param string $field Il campo da verificare (es. 'idUser', 'username').
     * @param mixed $value Il valore del campo.
     * @return bool True se esiste, False altrimenti.
     */
    public function existsUser(string $field, $value): bool
    {
        $db = FDatabase::getInstance();
        return $db->exists(static::TABLE_NAME, [$field => $value]);
    }

    /**
     * Aggiorna un utente esistente nel database.
     *
     * @param EUser $user L'oggetto utente da aggiornare.
     * @param int $id L'ID dell'utente da aggiornare.
     * @return bool True se l'aggiornamento ha avuto successo, False altrimenti.
     */
    public function updateUser(EUser $user): bool
    {
        $db = FDatabase::getInstance();
        $data = $this->userToArray($user);
        return $db->update(static::TABLE_NAME, $data, ['idUser' => $user->getIdUser()]);
    }

    /**
     * Blocca un utente nel database.
     *
     * @param int $idUser L'ID dell'utente.
     * @param string $motivation La motivazione del blocco.
     * @return bool True se l'operazione ha successo, False altrimenti.
     */
    public function banUser(int $idUser, string $motivation): bool
    {
        $db = FDatabase::getInstance();
        return $db->update(static::TABLE_NAME, ['ban' => 1, 'motivation' => $motivation], ['idUser' => $idUser]);
    }

    /**
     * Restituisce un elenco di utenti bloccati dal database.
     *
     * @return EUser[] Un array di oggetti EUser che rappresentano gli utenti bloccati.
     * @throws Exception Se si verifica un errore durante il caricamento.
     */
    public function getBlockedUsers(): array
    {
        $db = FDatabase::getInstance();
        $results = $db->fetchWhere(static::TABLE_NAME, ['ban' => 1]);
        return array_map([$this, 'createEntityUser'], $results);
    }

    /**
     * Elimina un utente dal database dato il suo ID.
     *
     * @param int $id L'ID dell'utente da eliminare.
     * @return bool True se l'eliminazione ha avuto successo, False altrimenti.
     */
    public function deleteUser(int $id): bool
    {
        $db = FDatabase::getInstance();
        return $db->delete(static::TABLE_NAME, ['idUser' => $id]);
    }

    /**
     * Carica un utente dal database dato il suo username.
     *
     * @param string $username L'username dell'utente da leggere.
     * @return EUser|null L'oggetto utente se trovato, null altrimenti.
     * @throws Exception
     */
    public function readUserByUsername(string $username): ?EUser
    {
        $db = FDatabase::getInstance();
        $result = $db->load(static::TABLE_NAME, 'username', $username);
        return $result ? $this->createEntityUser($result) : null;
    }

    /**
     * Legge tutti gli utenti dal database.
     *
     * @return EUser[] Un array di oggetti EUser.
     */
    public function readAllUsers(): array
    {
        $db = FDatabase::getInstance();
        $results = $db->fetchAllFromTable(static::TABLE_NAME);
        return array_map([$this, 'createEntityUser'], $results);
    }

    /**
     * Restituisce un utente dal database dato il suo ID.
     *
     * @param int $idUser L'ID dell'utente.
     * @return EUser|null L'oggetto utente se trovato, null altrimenti.
     * @throws Exception Se si verifica un errore durante il caricamento.
     */
    public function getUserById(int $idUser): ?EUser
    {
        try {
            $db = FDatabase::getInstance();
            $result = $db->load(static::TABLE_NAME, 'idUser', $idUser);
            return $result ? $this->createEntityUser($result) : null;
        } catch (Exception $e) {
            error_log("Errore durante il recupero dell'utente per ID: " . $e->getMessage());
            return null;
        }
    }

}
