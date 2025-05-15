<?php

namespace Foundation;

use Entity\EExtra;
use Exception;

/**
 * Class FExtra to manage extra items in the database.
 */
class FExtra {
    private FDatabase $db;

    protected const TABLE_NAME = 'extra';

    public function __construct(FDatabase $db = null) {
        $this->db = $db ?: FDatabase::getInstance();
    }

    /**
     * Creates an EExtra entity from an array of data.
     *
     * @param array $data The data to create the entity.
     * @return EExtra|null The EExtra instance if data is valid, null otherwise.
     */
    private function createEntityExtra(array $data): ?EExtra {
        if (isset($data['idExtra'], $data['nameExtra'], $data['priceExtra'])) {
            return new EExtra(
                $data['idExtra'],
                $data['nameExtra'],
                $data['priceExtra']
            );
        }
        return null;
    }

    /**
     * Create an EExtra object in the database.
     *
     * @param EExtra $extra The EExtra object to store.
     * @return int|null The ID of the stored extra.
     */
    public function createExtra(EExtra $extra): ?int {
        $data = [
            'nameExtra' => $extra->getNameExtra(),
            'priceExtra' => $extra->getPriceExtra(),
        ];
        try {
            return $this->db->insert(self::TABLE_NAME, $data);
        } catch (Exception $e) {
            error_log("Error storing extra: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Read an EExtra object from the database.
     *
     * @param int $id The ID of the extra to load.
     * @return EExtra|null The loaded EExtra object, or null if not found.
     * @throws Exception If there is an error during the load operation.
     */
    public function readExtra(int $id): ?EExtra {
        try {
            $result = $this->db->load(self::TABLE_NAME, 'idExtra', $id);
            return $this->createEntityExtra($result);
        } catch (Exception $e) {
            error_log("Error loading extra: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Updates an EExtra object in the database.
     *
     * @param int $idExtra
     * @param array $newData
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateExtra(int $idExtra, array $newData): bool {
        try {
            return $this->db->update(self::TABLE_NAME, $newData, ['idExtra' => $idExtra]);
        } catch (Exception $e) {
            error_log("Error updating extra: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes an EExtra object from the database.
     *
     * @param int $idExtra
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteExtra(int $idExtra): bool {
        try {
            return $this->db->delete(self::TABLE_NAME, ['idExtra' => $idExtra]);
        } catch (Exception $e) {
            error_log("Error deleting extra: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all extra items.
     *
     * @return EExtra[] An array of all EExtra objects.
     * @throws Exception If there is an error during the retrieval operation.
     */
    public function readAllExtra(): array {
        try {
            $db = FDatabase::getInstance(); // Get the singleton instance
            $results = $db->loadMultiples(self::TABLE_NAME); // Use the loadMultiples method to load the data
            return array_filter(array_map([$this, 'createEntityExtra'], $results));
        } catch (Exception $e) {
            error_log("Error loading all extras: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Checks if an extra exists in the database with the given ID.
     *
     * @param int $idExtra
     * @return bool True if the extra exists, false otherwise.
     */
    public function existsExtra(int $idExtra): bool {
        try {
            return $this->db->exists(self::TABLE_NAME, ['idExtra' => $idExtra]);
        } catch (Exception $e) {
            error_log("Error checking extra existence: " . $e->getMessage());
            return false;
        }
    }
}