<?php

namespace Foundation;

use Entity\ETable;
use Entity\EArea;
use Exception;

/**
 * Class FTable
 * @package Foundation
 * Handles the persistence of Table objects in the database.
 */
class FTable extends FArea {
    protected const TABLE_NAME = 'table';

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
     * Stores a Table object in the database.
     *
     * @param ETable $room
     * @return int The ID of the newly inserted table.
     * @throws Exception If an error occurs during the insertion.
     */
    public static function storeTable(ETable $table): int
    {
        $db = FDatabase::getInstance();
        $data = [
            'idTable' => $table->getIdTable(),
            'areaName' => $table->getAreaName(),
            'maxGuests' => $table->getMaxGuests()
        ];
        $id = $db->insert(self::TABLE_NAME, $data);
        if ($id === null) {
            throw new Exception('Error during the insertion of the room.');
        }
        return $id;
    }

    /**
     * Loads a table from the database based on its ID.
     *
     * @param int $id
     * @return ETable|null
     * @throws Exception If an error occurs during the loading.
     */
    public static function loadTable(int $id): ?ETable
    {
        $db = FDatabase::getInstance();
        $result = $db->load(self::TABLE_NAME, 'idTable', $id);
        return $result ? self::createTableFromRow($result) : null;
    }

    /**
     * Loads a list of tables from the database.
     *
     * @return ETable[]
     * @throws Exception If an error occurs during the loading.
     */
    public static function loadAllTable(): array
    {
        $db = FDatabase::getInstance();
        $result = $db->loadMultiples(self::TABLE_NAME);
        return array_map([self::class, 'createTableFromRow'], $result);
    }

    /**
     * Updates a table in the database.
     *
     * @param ETable $room
     * @return bool
     * @throws Exception If an error occurs during the update.
     */
    public static function updateTable(ETable $table): bool
    {
        $db = FDatabase::getInstance();
        $data = [
            'idTable' => $table->getIdTable(),
            'areaName' => $table->getAreaName(),
            'maxGuests' => $table->getMaxGuests()
        ];
        return $db->update(self::TABLE_NAME, $data, ['idRoom' => $table->getIdTable()]);
    }

    /**
     * Deletes a table from the database.
     *
     * @param int $idTable
     * @return bool
     * @throws Exception If an error occurs during the deletion.
     */
    public static function deleteTable(int $idTable): bool
    {
        $db = FDatabase::getInstance();
        return $db->delete(self::TABLE_NAME, ['idTable' => $idTable]);
    }

    /**
     * Creates an ETable object from a database row.
     *
     * @param array $row
     * @return ETable
     */
    private static function createTableFromRow(array $row): ETable
    {
        return new ETable(
            $row['idTable'],
            $row['areaName'],
            $row['maxGuests']
        );
    }

    /**
     * Checks if a table exists.
     *
     * @param int $idTable
     * @return bool
     * @throws Exception
     */
    public static function existsTable(int $idTable): bool
    {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['idTable' => $idTable]);
    }

    /**
     * Searches for table by name.
     *
     * @param string $name The name to search for.
     * @return array An array of ETable objects that match the name.
     * @throws Exception If there is an error during the search operation.
     */
    public static function searchByName(string $name): array
    {
        $db = FDatabase::getInstance();
        $result = $db->fetchLike(self::TABLE_NAME, 'name', $name);
        return array_map([self::class, 'createTableFromRow'], $result);
    }
}