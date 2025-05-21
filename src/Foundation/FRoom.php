<?php

namespace Foundation;

use Entity\ERoom;
use Exception;

/**
 * Class FRoom
 * Handles CRUD operations for rooms in the database.
 */
class FRoom {
    /**
     * Name of the table associated with the room entity in the database.
     */
    protected const TABLE_NAME = 'room';

    /**
     * Returns the name of the table associated with rooms.
     *
     * @return string The name of the table.
     */
    public function getTableName(): string {
        return static::TABLE_NAME;
    }

    // Error messages centralized for consistency
    protected const ERR_INSERTION_FAILED = 'Error during the insertion of the room.';
    protected const ERR_RETRIVE_ROOM='Failed to retrive the inserted room.';
    protected const ERR_ROOM_NOT_FOUND = 'The room does not exist.';
    protected const  ERR_UPDATE_FAILED = 'Error during the update operation.';

    /**
     * Creates an instance of ERoom from the given data.
     *
     * @param array $data The data array containing room information.
     * @return ERoom The created ERoom object.
     */
    public function arrayToEntity(array $data): ERoom { //NOTA: mancano i soliti check sui dati perchè sono informazioni che non vengono toccate da nessun utente della Web App
        return new ERoom(
            $data['idRoom'] ?? null,
            $data['areaName'] ?? null,
            $data['maxGuests'] ?? null,
            $data['tax'] ?? null
        );
    }

    /**
     * Converts an ERoom object into an associative array for the database.
     *
     * @param ERoom $room The room object to convert.
     * @return array The room data as an array.
     */
    private function entityToArray(ERoom $room): array {
        return [
            'idRoom' => $room->getIdRoom(),
            'areaName' => $room->getAreaName(),
            'maxGuests' => $room->getMaxGuests(),
            'tax' => $room->getTax(),
        ];
    }

    /**
     * Creates a new room in the database.
     *
     * @param ERoom $room The room object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     */
    public function create(ERoom $room): int {
            $db = FDatabase::getInstance();
            $data = $this->entityToArray($room);
        try {
            //Room insertion
            $result = $db->insert(self::TABLE_NAME, $data);
            if ($result === null) {
                throw new Exception(self::ERR_INSERTION_FAILED);
            }
            //Get last Inserted ID
            $idInserito=$db->getLastInsertedId();
            if ($idInserito==null) {
                throw new Exception("Impossibile recuperare l'Id della stanza");
            }
            //Retrive the inserted room by number to get the assigned idRoom
            $storedRoom = $db->load(self::TABLE_NAME, 'idRoom', $idInserito);
            if ($storedRoom === null) {
                throw new Exception(self::ERR_RETRIVE_ROOM);
            }
            //Assign the retrieved ID to the object
            $room->setIdRoom((int)$idInserito);
            //Return the id associated with this room
            return $idInserito;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Reads a room from the database by their ID.
     *
     * @param int $id The ID of the room to read.
     * @return ERoom|null The room object if found, null otherwise.
     * @throws Exception
     */
    public function read(int $id): ?ERoom {
        $db = FDatabase::getInstance();
        $result = $db->load(static::TABLE_NAME, 'idRoom', $id);
        return $result ? $this->arrayToEntity($result) : null;
    }

    /**
     * Updates an existing room in the database.
     *
     * @param ERoom $reply The room object to update.
     * @param int $id The ID of the room to update.
     * @return bool True if the update was successful, False otherwise.
     */
    public function update(ERoom $room): bool {
        $db = FDatabase::getInstance();
        if (!self::exists($room->getIdRoom())) {
            throw new Exception(self::ERR_ROOM_NOT_FOUND);
        }
        $data = [
            'areaName' => $room->getAreaName(),
            'maxGuests' => $room->getMaxGuests(),
            'tax' => $room->getTax(),
        ];
        if (!$db->update(self::TABLE_NAME, $data, ['idRoom' => $room->getIdRoom()])) {
            throw new Exception(self::ERR_UPDATE_FAILED);
        }
        return true;
    }

    /**
     * Deletes a room from the database by their ID.
     *
     * @param int $id The ID of the room to delete.
     * @return bool True if the deletion was successful, False otherwise.
     */
    public function delete(int $id): bool {
        $db = FDatabase::getInstance();
        return $db->delete(static::TABLE_NAME, ['idRoom' => $id]);
    }

    /**
     * Checks if a room exists in the database based on a specific field.
     *
     * @param string $field The field to check (e.g., 'idRoom', 'areaName').
     * @param mixed $value The value of the field.
     * @return bool True if it exists, False otherwise.
     */
    public static function exists(int $idRoom): bool {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['idRoom' => $idRoom]);
    }

    /**
     * Loads a list of rooms from the database.
     *
     * @return ERoom[]
     * @throws Exception If an error occurs during the loading.
     */
    public static function readAllRoom(): array {
        $db = FDatabase::getInstance();
        $result = $db->loadMultiples(self::TABLE_NAME);
        return array_map([self::class, 'arrayToEntity'], $result);
    }
}