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

    /**
     * Converts an ETable object into an associative array for the database.
     *
     * @param ETable $table The table object to convert.
     * @return array The table data as an array.
     */
    private function tableToArray(ETable $table): array {
        return [
            'idTable' => $table->getIdTable(),
            'areaName' => $table->getAreaName(),
            'maxGuests' => $table->getMaxGuests(),
        ];
    }

    /**
     * Creates a new instance of ETable with the provided data
     * 
     * @param int $idTable
     * @param string $areaName
     * @param int $maxGuests
    */
    public static function createEntityTable(array $data): ETable {
        return new ETable(
            $data['idRoom'] ?? null,
            $data['areaName'] ?? null,
            $data['maxGuests'] ?? null
        );
    }

    /**
     * Creates a new table in the database.
     *
     * @param ETable $table The table object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     */
    public function create(ETable $table): int {
        try {
            $db = FDatabase::getInstance();
            $data = $this->tableToArray($table);
            return $db->insert(static::TABLE_NAME, $data) ?: 0;
        } catch (Exception $e) {
            error_log("Errore durante l'inserimento del tavolo: " . $e->getMessage());
            return 0; // Or use a different error handling approach
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
        return $result ? $this->createEntityTable($result) : null;
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
        $data = $this->tableToArray($table);
        return $db->update(static::TABLE_NAME, $data, ['idTable' => $table->getIdTable()]);
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
    public static function existsTable(int $idTable): bool {
        $db = FDatabase::getInstance();
        $exists = $db->exists(self::TABLE_NAME, ['idTable' => $idTable]);
        return $exists;
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