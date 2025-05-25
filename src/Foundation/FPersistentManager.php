<?php

namespace Foundation;

use AllowDynamicProperties;
use DateTime;
use Entity\EArea;
use Entity\ECreditCard;
use Entity\EExtra;
use Entity\EPayment;
use Entity\EReply;
use Entity\EReservation;
use Entity\EReview;
use Entity\ERoom;
use Entity\ETable;
use Entity\EUser;
use Entity\Role;
use Exception;

/**
 * Class FPersistentManager
 * @package Foundation
 * Handles the persistence of objects in the database.
 */
#[AllowDynamicProperties] class FPersistentManager
{
    private static ?FPersistentManager $instance = null;

    /**
     * FPersistentManager constructor.
     * Private constructor to implement the Singleton pattern.
     */
    private function __construct()
    {
        $this->db = FDatabase::getInstance();
    }

    /**
     * Returns the singleton instance of FPersistentManager.
     *
     * @return FPersistentManager
     */
    public static function getInstance(): FPersistentManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Create an object in the database.
     *
     * @param object $obj The object to store.
     * @return int The ID of the stored object.
     * @throws Exception If the class or method is not found or if an error occurs during the store operation.
     */
    public function create(object $obj): int {
        return $this->performOperation('create', $this->getClassName($obj), $obj);
    }

    /**
     * Read an object from the database.
     *
     * @param int $id The ID of the object to load.
     * @param string $fClass The associated foundation class name.
     * @return object|null The loaded object, or null if not found.
     * @throws Exception If the class or method is not found.
     */
    public function read(int $id, string $fClass): ?object {
        return $this->performOperation('read', $fClass, $id);
    }

    /**
     * Updates an object in the database.
     *
     * @param object $obj The object to update.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If the class or method is not found or if an error occurs during the update operation.
     */
    public function update(object $obj): bool {
        return $this->performOperation('update', $this->getClassName($obj), $obj);
    }

    /**
     * Deletes an object from the database.
     *
     * @param int $id The ID of the object to delete.
     * @param string $fClass The associated foundation class name.
     * @return bool True if the deletion was successful, false otherwise.
     * @throws Exception If the class or method is not found or if an error occurs during the delete operation.
     */
    public function delete(int $id, string $fClass): bool {
        return $this->performOperation('delete', $fClass, $id);
    }

    /**
     * Check if an object exists in db from the Id
     * 
     * @param int the object Id, like useId, reviewId, ecc
     * @return bool true if founded, false not founded
     */
    public function exists(int $id, string $fClass): bool {
        return $this->performOperation('exists', $fClass, $id);
    }
    
    /**
     * Loads a list of objects from the database.
     *
     * @param string $fClass The associated foundation class name.
     * @return array A list of objects.
     * @throws Exception If the class or method is not found.
     */
    public function readAll(string $fClass): array
    {
        return $this->performOperation('readAll', $fClass);
    }

    /**
     * Loads a user from the database.
     *
     * @param string $username The username of the user to load.
     * @return EUser|null Returns an EUser object if found in the database, otherwise null.
     * @throws Exception If an error occurs during the load operation.
     */
    public function loadUser(string $username): ?EUser
    {
        return $this->loadByField(FUser::class, 'username', $username);
    }

    /**
     * Loads an admin User from the database using the username.
     *
     * @param string $username The username of the admin User to load.
     * @return EUser|null The EAdmin User object if found, otherwise null.
     * @throws Exception If an error occurs during the load operation.
     */
    public function loadAdmin(string $username): ?EUser
    {
        $user=$this->loadByField(FUser::class, 'username', $username);
        if ($user !== null && $user->getRole() === Role::AMMINISTRATORE) {
            return $user;
        }
        return null;
    }

    /**
     * Loads all free rooms in the specified period.
     *
     * @param DateTime $start The start date of the period.
     * @param DateTime $end The end date of the period.
     * @return array An array of free rooms.
     * @throws Exception If an error occurs during the load operation.
     */
    public function loadFreeRooms(DateTime $start, DateTime $end): array
    {
        return $this->performOperation('loadFreeRooms', FRoom::class, $start, $end);
    }

    /**
     * Loads all free tables in the specified period.
     *
     * @param DateTime $start The start date of the period.
     * @param DateTime $end The end date of the period.
     * @return array An array of free tables.
     * @throws Exception If an error occurs during the load operation.
     */
    public function loadFreeTables(DateTime $start, DateTime $end): array
    {
        return $this->performOperation('loadFreeTables', FTable::class, $start, $end);
    }

    /**
     * Loads a reservation from the database.
     *
     * @param int $id The ID of the reservation to load.
     * @return EReservation|null The EReservation object if found, otherwise null.
     * @throws Exception If an error occurs during the load operation.
     */
    public function loadReservation(int $id): ?EReservation
    {
        return $this->performOperation('load', FReservation::class, $id);
    }

    /**
     * Loads all reviews from the database.
     *
     * @return array An array of EReview objects.
     * @throws Exception If an error occurs during the load operation.
     */
    public function loadAllRec(): array
    {
        return $this->performOperation('loadAll', FReview::class);
    }

    /**
     * Gets the class name for an entity object.
     *
     * @param object $entity The entity object.
     * @return string The corresponding Foundation class name.
     * @throws Exception If the entity class is not recognized.
     */
    private function getClassName(object $entity): string
    {
        $classMap = [
            ECreditCard::class => FCreditCard::class,
            EExtra::class => FExtra::class,
            EPayment::class => FPayment::class,
            EReply::class => FReply::class,
            EReservation::class => FReservation::class,
            EReview::class => FReview::class,
            ERoom::class => FRoom::class,
            ETable::class => FTable::class,
            EUser::class => FUser::class
        ];

        $class = get_class($entity);
        if (!isset($classMap[$class])) {
            throw new Exception("Unrecognized entity class: $class");
        }

        return $classMap[$class];
    }

    /**
     * Invokes a method on a Foundation class.
     *
     * @param string $className The Foundation class name.
     * @param string $method The method name.
     * @param mixed ...$params The parameters to pass to the method.
     * @return mixed The result of the method call.
     * @throws Exception If the method is not found.
     */
    private function invokeMethod(string $className, string $method, mixed ...$params): mixed
    {
        if (!method_exists($className, $method)) {
            throw new Exception("Method not found: $className::$method");
        }

        $instance=new $className();
        return $instance->$method(...$params);
    }

    /**
     * Helper method to perform operations on Foundation classes.
     *
     * @param string $operation The operation to perform.
     * @param string $className The Foundation class name.
     * @param mixed ...$params Parameters for the operation.
     * @return mixed The result of the operation.
     * @throws Exception If the operation fails.
     */
    private function performOperation(string $operation, string $className, mixed ...$params): mixed
    {
        return $this->invokeMethod($className, $operation, ...$params);
    }

    /**
     * Loads an entity by a specific field.
     *
     * @param string $fClass The Foundation class name.
     * @param string $field The field to search by.
     * @param mixed $value The value of the field.
     * @return object|null The loaded entity, or null if not found.
     * @throws Exception If an error occurs during the load operation.
     */
    private function loadByField(string $fClass, string $field, mixed $value): ?object
    {
        return $this->performOperation('loadByField', $fClass, $field, $value);
    }
}
