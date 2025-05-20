<?php

namespace Foundation;

use Entity\ERoom;
use Entity\EArea;
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

    /**
     * Converts an ERoom object into an associative array for the database.
     *
     * @param ERoom $room The room object to convert.
     * @return array The room data as an array.
     */
    private function roomToArray(ERoom $room): array {
        return [
            'idRoom' => $room->getIdRoom(),
            'areaName' => $room->getAreaName(),
            'maxGuests' => $room->getMaxGuests(),
            'tax' => $room->getTax(),
        ];
    }

    /**
     * Creates a new instance of ERoom with the provided data
     * 
     * @param int $idRoom
     * @param string $areaName
     * @param int $maxGuests
     * @param float $tax
    */
    public static function createEntityRoom(array $data): ERoom {
        return new ERoom(
            $data['idRoom'] ?? null,
            $data['areaName'] ?? null,
            $data['maxGuests'] ?? null,
            $data['tax'] ?? null
        );
    }

    /**
     * Creates a new room in the database.
     *
     * @param ERoom $room The room object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     */
    public function create(ERoom $room): int {
        try {
            $db = FDatabase::getInstance();
            $data = $this->roomToArray($room);
            return $db->insert(static::TABLE_NAME, $data) ?: 0;
        } catch (Exception $e) {
            error_log("Errore durante l'inserimento della stanza: " . $e->getMessage());
            return 0; // Or use a different error handling approach
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
        return $result ? $this->createEntityRoom($result) : null;
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
        $data = $this->roomToArray($room);
        return $db->update(static::TABLE_NAME, $data, ['idRoom' => $room->getIdRoom()]);
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
    public static function existsRoom(int $idRoom): bool {
        $db = FDatabase::getInstance();
        $exists = $db->exists(self::TABLE_NAME, ['idRoom' => $idRoom]);
        return $exists;
    }

    /**
     * Loads a list of rooms from the database.
     *
     * @return ERoom[]
     * @throws Exception If an error occurs during the loading.
     */
    public static function loadAllRoom(): array {
        $db = FDatabase::getInstance();
        $result = $db->loadMultiples(self::TABLE_NAME);
        return array_map([self::class, 'createEntityRoom'], $result);
    }
}