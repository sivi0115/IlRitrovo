<?php

namespace Foundation;

use Entity\ERoom;
use Exception;

/**
 * Class FRoom
 * @package Foundation
 * Handles the persistence of Room objects in the database.
 */
class FRoom
{
    protected const TABLE_NAME = 'room';

    public function __construct() {} // Private constructor

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
            'name' => $room->getName(),
            'capacity' => $room->getCapacity(),
            'squareFootage' => $room->getSquareFootage(),
            'photo' => $room->getPhoto(),
            'price' => $room->getPrice(),
            'idLocation' => $room->getIdLocation(), // Include idLocation in the data
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
     * Updates a room in the database.
     *
     * @param ERoom $room
     * @return bool
     * @throws Exception If an error occurs during the update.
     */
    public static function updateRoom(ERoom $room): bool
    {
        $db = FDatabase::getInstance();
        $data = [
            'name' => $room->getName(),
            'squareFootage' => $room->getSquareFootage(),
            'capacity' => $room->getCapacity(),
            'photo' => $room->getPhoto(),
            'price' => $room->getPrice(),
            'idLocation' => $room->getIdLocation(), // Include idLocation in the data
        ];
        return $db->update(self::TABLE_NAME, $data, ['idRoom' => $room->getIdRoom()]);
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
            $row['name'],
            $row['capacity'],
            $row['squareFootage'],
            $row['photo'],
            $row['price'],
            $row['idLocation']
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
     * @param float $price The new price of the room.
     * @param int $idRoom The ID of the room to update.
     * @param float $squareFootage The new square footage of the room.
     * @param int $capacity The new capacity of the room.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If there is an error during the update operation.
     */
    public static function updateInfoRoom(float $price, int $idRoom, float $squareFootage, int $capacity): bool
    {
        $db = FDatabase::getInstance();
        $data = [
            'price' => $price,
            'squareFootage' => $squareFootage,
            'capacity' => $capacity
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
        $result = $db->fetchBetween(self::TABLE_NAME, 'price', $minPrice, $maxPrice);
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
