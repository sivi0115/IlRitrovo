<?php

namespace Foundation;

use Entity\EArea;
use Exception;

/**
 * Class FArea
 * Abstract class that manages the persistence of EArea objects.
 * Extended by FRoom and FTable.
 */
abstract class FArea {
    protected FPersistentManager $persistentManager;

    /**
     * FPerson constructor.
     * Initialize the Persistent Manager.
     */
    public function __construct() {
        $this->persistentManager = FPersistentManager::getInstance();
    }

    /**
     * Returns the table name specific to the derived class.
     * This method will be implemented in the subclasses.
     *
     * @return string The name of the table.
     */
    abstract public function getTableName(): string;

    /**
     * Loads an area from the database based on the ID.
     *
     * @param int $id The ID of the area to load.
     * @return EArea|null The loaded EArea object, or null if not found.
     * @throws Exception If there are errors during the loading process.
     */
    public function load(int $id): ?EArea {
        $result = FDatabase::getInstance()->load($this->getTableName(), 'idArea', $id);
        if ($result) {
            return $this->createEntity($result);
        }
        return null;
    }

    /**
     * Deletes an area from the database based on the ID.
     *
     * @param int $id The ID of the area to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(int $id): bool {
        return FDatabase::getInstance()->delete($this->getTableName(), ['id' => $id]);
    }

    /**
     * Saves an area to the database (insertion).
     *
     * @param EArea $area The area object to save.
     * @return int The ID of the saved record.
     * @throws Exception If there are errors during the saving process.
     */
    public function save(EArea $area): int {
        $data = $this->prepareData( $area);
        return FDatabase::getInstance()->insert($this->getTableName(), $data);
    }

    /**
     * Updates an area in the database based on the ID.
     *
     * @param EArea $area The area object to update.
     * @param int $id The ID of the area to update.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(EArea $area, int $id): bool {
        $data = $this->prepareData($area);
        return FDatabase::getInstance()->update($this->getTableName(), $data, ['id' => $id]);
    }    

    /**
     * Protected method to create an EArea object from data retrieved from the database.
     *
     * @param array $data The area data from the database.
     * @return EArea The created EArea object.
     * @throws Exception If there are errors while parsing the data.
     */
    public function createEntity(array $data): EArea {
        return new EArea(
            $data['areaName'],
            $data['maxGuests'],
        );
    }

    /**
     * Prepares the data of an EArea object to be saved in the database.
     *
     * @param EArea $area The area object from which to extract the data.
     * @return array The prepared data.
     */
    protected function prepareData(EArea $area): array {
        return [
            'areaName' => $area->getareaName(),
            'maxGuests' => $area->getMaxGuests(),
        ];
    }
}