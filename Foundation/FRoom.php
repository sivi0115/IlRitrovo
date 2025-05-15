<?php

namespace Foundation;

use Entity\ERoom;
use Entity\EArea;
use Exception;

/**
 * Class FRoom
 * @package Foundation
 * Handles the persistence of Room objects in the database.
 */
class FRoom extends FArea
{
    protected const TABLE_NAME = 'room';

    // Private constructor
    public function __construct(FDatabase $database) {
        $this->database = $database;
        parent::__construct();
    }

    /**
     * Returns the name of the database table associated with the Table entity.
     *
     * This method provides the specific table name used for
     * all database operations related to tables.
     *
     * @return string The name of the database table.
     */
    public function getTableName(): string {
        return 'tables';
    }

    /**
     * Stores a Room object in the database.
     *
     * @param ERoom $room
     * @return int The ID of the newly inserted room.
     * @throws Exception If an error occurs during the insertion.
     */
    public static function storeRoom(ERoom $room): int
    {
        $db = FDatabase::getInstance();
        $data = [
            'idRoom' => $room->getIdRoom(),
            'areaName' => $room->getAreaName(),
            'maxGuests' => $room->getMaxGuests(),
            'tax' => $room->getTax(),
        ];
        $id = $db->insert(self::TABLE_NAME, $data);
        if ($id === null) {
            throw new Exception('Error during the insertion of the room.');
        }
        return $id;
    }

    /**
     * Loads a room from the database based on its ID.
     *
     * @param int $id
     * @return ERoom|null
     * @throws Exception If an error occurs during the loading.
     */
    public static function loadRoom(int $id): ?ERoom
    {
        $db = FDatabase::getInstance();
        $result = $db->load(self::TABLE_NAME, 'idRoom', $id);
        return $result ? self::createRoomFromRow($result) : null;
    }

    /**
     * Loads a list of rooms from the database.
     *
     * @return ERoom[]
     * @throws Exception If an error occurs during the loading.
     */
    public static function loadAllRoom(): array
    {
        $db = FDatabase::getInstance();
        $result = $db->loadMultiples(self::TABLE_NAME);
        return array_map([self::class, 'createRoomFromRow'], $result);
    }

    /**
     * Deletes a room from the database.
     *
     * @param int $idRoom
     * @return bool
     * @throws Exception If an error occurs during the deletion.
     */
    public static function deleteRoom(int $idRoom): bool
    {
        $db = FDatabase::getInstance();
        return $db->delete(self::TABLE_NAME, ['idRoom' => $idRoom]);
    }

    /**
     * Creates an ERoom object from a database row.
     *
     * @param array $row
     * @return ERoom
     */
    private static function createRoomFromRow(array $row): ERoom
    {
        return new ERoom(
            $row['idRoom'],
            $row['areaName'],
            $row['maxGuests'],
            $row['tax']
        );
    }

    /**
     * Checks if a room exists.
     *
     * @param int $idRoom
     * @return bool
     * @throws Exception
     */
    public static function existsRoom(int $idRoom): bool
    {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['idRoom' => $idRoom]);
    }

    /**
     * Updates the price, square footage, and capacity of a room.
     *
     * @param float $tax The new price of the room.
     * @param int $idRoom The ID of the room to update.
     * @param int $maxGuests The new capacity of the room.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If there is an error during the update operation.
     */
    public static function updateInfoRoom(float $tax, int $idRoom, int $maxGuests): bool
    {
        $db = FDatabase::getInstance();
        $data = [
            'tax' => $tax,
            'maxGuests' => $maxGuests
        ];
        return $db->update(self::TABLE_NAME, $data, ['idRoom' => $idRoom]);
    }

    /**
     * Searches for rooms by name.
     *
     * @param string $name The name to search for.
     * @return array An array of ERoom objects that match the name.
     * @throws Exception If there is an error during the search operation.
     */
    public static function searchByName(string $name): array
    {
        $db = FDatabase::getInstance();
        $result = $db->fetchLike(self::TABLE_NAME, 'name', $name);
        return array_map([self::class, 'createRoomFromRow'], $result);
    }

    /**
     * Finds rooms based on price.
     *
     * @param float $minPrice
     * @param float $maxPrice
     * @return ERoom[]
     * @throws Exception If an error occurs during the loading.
     */
    public static function findRoomsByPrice(float $minPrice, float $maxPrice): array
    {
        $db = FDatabase::getInstance();
        $result = $db->fetchBetween(self::TABLE_NAME, 'capacity', $minPrice, $maxPrice);
        return array_map([self::class, 'createRoomFromRow'], $result);
    }

    /**
     * Finds rooms based on capacity.
     *
     * @param int $minCapacity
     * @param int $maxCapacity
     * @return ERoom[]
     * @throws Exception If an error occurs during the loading.
     */
    public static function findRoomsByCapacity(int $minCapacity, int $maxCapacity): array
    {
        $db = FDatabase::getInstance();
        $result = $db->fetchBetween(self::TABLE_NAME, 'capacity', $minCapacity, $maxCapacity);
        return array_map([self::class, 'createRoomFromRow'], $result);
    }

    /**
     * Searches for available rooms based on multiple criteria.
     *
     * @param float $minPrice
     * @param float $maxPrice
     * @param int $minCapacity
     * @param int $maxCapacity
     * @return ERoom[]
     * @throws Exception If an error occurs during the search.
     */
    public static function searchAvailableRooms(
        float $minPrice,
        float $maxPrice,
        int $minCapacity,
        int $maxCapacity
    ): array {
        $db = FDatabase::getInstance();

        $result = $db->fetchWhere(
            self::TABLE_NAME,
            ['price' => ['BETWEEN', $minPrice, $maxPrice], 'capacity' => ['BETWEEN', $minCapacity, $maxCapacity]]
        );
        $rooms = [];
        foreach ($result as $row) {
            $rooms[] = self::createRoomFromRow($row);
        }
        return $rooms;
    }

    /**
     * Retrieves rooms by their location.
     *
     * @param int $idLocation The location ID to filter rooms by.
     * @return ERoom[] An array of ERoom objects matching the location.
     * @throws Exception If an error occurs during the search.
     */
    public static function getRoomsByLocation(int $idLocation): array
    {
        $db = FDatabase::getInstance();
        $result = $db->fetchWhere(self::TABLE_NAME, ['idLocation' => $idLocation]);
        return array_map([self::class, 'createRoomFromRow'], $result);
    }
}