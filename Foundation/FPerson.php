<?php

namespace Foundation;

use DateTime;
use Entity\EPerson;
use Exception;

/**
 * Class FPerson
 * Classe astratta che gestisce la persistenza di oggetti EPerson.
 * Viene estesa da FUser, FAdmin, FOwner.
 */
abstract class FPerson
{
    protected FPersistentManager $persistentManager;

    /**
     * FPerson constructor.
     * Inizializza il Persistent Manager.
     */
    public function __construct()
    {
        $this->persistentManager = FPersistentManager::getInstance();
    }

    /**
     * Ritorna il nome della tabella specifico per la classe derivata.
     * Questo metodo sarà implementato nelle sottoclassi.
     *
     * @return string Il nome della tabella.
     */
    abstract public function getTableName(): string;

    /**
     * Carica una persona dal database in base all'ID.
     *
     * @param int $id L'ID della persona da caricare.
     * @return EPerson|null L'oggetto EPerson caricato o null se non trovato.
     * @throws Exception Se ci sono errori durante il caricamento.
     */
    public function load(int $id): ?EPerson
    {
        $result = FDatabase::getInstance()->load($this->getTableName(), 'id', $id);
        if ($result) {
            return $this->createEntity($result);
        }
        return null;
    }

    /**
     * Cancella una persona dal database in base all'ID.
     *
     * @param int $id L'ID della persona da cancellare.
     * @return bool True se la cancellazione ha avuto successo, false altrimenti.
     */
    public function delete(int $id): bool
    {
        return FDatabase::getInstance()->delete($this->getTableName(), 'id', $id);
    }

    /**
     * Salva una persona nel database (inserimento).
     *
     * @param EPerson $person L'oggetto persona da salvare.
     * @return int L'ID del record salvato.
     * @throws Exception Se ci sono errori durante il salvataggio.
     */
    public function save(EPerson $person): int
    {
        $data = $this->prepareData($person);
        return FDatabase::getInstance()->insert($this->getTableName(), $data);
    }

    /**
     * Aggiorna una persona nel database in base all'ID.
     *
     * @param EPerson $person L'oggetto persona da aggiornare.
     * @param int $id L'ID della persona da aggiornare.
     * @return bool True se l'aggiornamento ha avuto successo, false altrimenti.
     */
    public function update(EPerson $person, int $id): bool
    {
        $data = $this->prepareData($person);
        return FDatabase::getInstance()->update($this->getTableName(), $data, 'id', $id);
    }

    /**
     * Metodo protetto per creare un oggetto EPerson dai dati estratti dal database.
     *
     * @param array $data I dati della persona dal database.
     * @return EPerson L'oggetto EPerson creato.
     * @throws Exception Se ci sono errori nel parsing dei dati.
     */
    public function createEntity(array $data): EPerson
    {
        return new EPerson(
            $data['username'],
            $data['name'],
            $data['surname'],
            new DateTime($data['birthDate']),
            $data['phone'],
            $data['image'],
            $data['email'],
            $data['password']
        );
    }

    /**
     * Prepara i dati di un oggetto EPerson per essere salvati nel database.
     *
     * @param EPerson $person L'oggetto persona da cui estrarre i dati.
     * @return array I dati preparati.
     */
    protected function prepareData(EPerson $person): array
    {
        return [
            'username' => $person->getUsername(),
            'name' => $person->getName(),
            'surname' => $person->getSurname(),
            'birthDate' => $person->getBirthDate()->format('Y-m-d'),
            'phone' => $person->getPhone(),
            'image' => $person->getImage(),
            'email' => $person->getEmail(),
            'password' => $person->getPassword(),
        ];
    }

    /**
     * Hash della password usando l'algoritmo bcrypt.
     *
     * @param string $password La password in chiaro.
     * @return string La password hashed.
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
