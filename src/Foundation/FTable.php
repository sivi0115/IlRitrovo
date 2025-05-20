<?php

namespace Foundation;

use Entity\ETable;
use Exception;

/**
 * Class FTable
 * Handles CRUD and others operations for tables in the database.
 */
class FTable {
    /**
     * Name of the table associated with the table entity in the database.
     */
    protected const TABLE_NAME = 'table';
    
    /**
     * Returns the name of the table associated with tables.
     *
     * @return string The name of the table.
     */
    public function getTableName(): string {
        return static::TABLE_NAME;
    }

    // Error messages centralized for consistency
    protected const ERR_INSERTION_FAILED = 'Error during the insertion of the table.';
    protected const ERR_RETRIVE_TABLE='Failed to retrive the inserted table.';
    protected const ERR_TABLE_NOT_FOUND = 'The table does not exist.';
    protected const  ERR_UPDATE_FAILED = 'Error during the update operation.';

    /**
     * Creates a new instance of ETable with the provided data
     * 
     * @param int $idTable
     * @param string $areaName
     * @param int $maxGuests
    */
    public static function arrayToEntity(array $data): ETable { //NOTA: mancano i soliti check sui dati perchè sono informazioni che non vengono toccate da nessun utente della Web App
        return new ETable(
            $data['idRoom'] ?? null,
            $data['areaName'] ?? null,
            $data['maxGuests'] ?? null
        );
    }

    /**
     * Converts an ETable object into an associative array for the database.
     *
     * @param ETable $table The table object to convert.
     * @return array The table data as an array.
     */
    private function entityToArray(ETable $table): array {
        return [
            'idTable' => $table->getIdTable(),
            'areaName' => $table->getAreaName(),
            'maxGuests' => $table->getMaxGuests(),
        ];
    }

    /**
     * Creates a new table in the database.
     *
     * @param ETable $table The table object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     */
    public function create(ETable $table): int {
        $db = FDatabase::getInstance();
        $data = $this->entityToArray($table);
        try {
            //Table insertion
            $result = $db->insert(self::TABLE_NAME, $data);
            if ($result === null) {
                throw new Exception(self::ERR_INSERTION_FAILED);
            }
            //Retrive the inserted table by number to get the assigned idTable
            $storedTable = $db->load(self::TABLE_NAME, 'number', $table->getIdTable());
            if ($storedTable === null) {
                throw new Exception(self::ERR_RETRIVE_TABLE);
            }
            //Assign the retrieved ID to the object
            $table->setIdTable($storedTable['idTable']);
            //Return the id associated with this table
            return $storedTable['idTable'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Reads a table from the database by their ID.
     *
     * @param int $id The ID of the table to read.
     * @return ETable|null The table object if found, null otherwise.
     * @throws Exception
     */
    public function read(int $id): ?ETable {
        $db = FDatabase::getInstance();
        $result = $db->load(static::TABLE_NAME, 'idTable', $id);
        return $result ? $this->arrayToEntity($result) : null;
    }

    /**
     * Updates an existing table in the database.
     *
     * @param ETable $table The table object to update.
     * @param int $id The ID of the table to update.
     * @return bool True if the update was successful, False otherwise.
     */
    public function update(ETable $table): bool {
        $db = FDatabase::getInstance();
        if (!self::exists($table->getIdTable())) {
            throw new Exception(self::ERR_TABLE_NOT_FOUND);
        }
        $data = [
            'areaName' => $table->getAreaName(),
            'maxGuests' => $table->getMaxGuests(),
        ];
        if (!$db->update(self::TABLE_NAME, $data, ['idTable' => $table->getIdTable()])) {
            throw new Exception(self::ERR_UPDATE_FAILED);
        }
        return true;
    }

    /**
     * Deletes a table from the database by their ID.
     *
     * @param int $id The ID of the table to delete.
     * @return bool True if the deletion was successful, False otherwise.
     */
    public function delete(int $id): bool {
        $db = FDatabase::getInstance();
        return $db->delete(static::TABLE_NAME, ['idTable' => $id]);
    }

    /**
     * Checks if a table exists in the database based on a specific field.
     *
     * @param string $field The field to check (e.g., 'idTable', 'areaName').
     * @param mixed $value The value of the field.
     * @return bool True if it exists, False otherwise.
     */
    public static function exists(int $idTable): bool {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['idTable' => $idTable]);
    }

    /**
     * Loads a list of tables from the database.
     *
     * @return ETable[]
     * @throws Exception If an error occurs during the loading.
     */
    public static function loadAllTables(): array {
        $db = FDatabase::getInstance();
        $result = $db->loadMultiples(self::TABLE_NAME);
        return array_map([self::class, 'createEntityTable'], $result);
    }
}