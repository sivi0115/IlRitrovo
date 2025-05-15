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


    //DEMO DELLA FOUNDATION EXTRA
    public function createExtra(EExtra $extra): ?int {
        return $this->performOperation('createExtra', FExtra::class, $extra);
    }

    public function readExtra(int $idExtra): ?EExtra {
        return $this->performOperation('readExtra', EExtra::class, $idExtra); 
    }

    public function updateExtra(EExtra $extra, array $newData): ?bool {
        return $this->performOperation('updateExtra', FExtra::class, $extra, $newData);
    }

    public function deleteExtra(int $idExtra): bool {
        return $this->performOperation('deleteExtra', FExtra::class, $idExtra);
    }

    public function readAllExtra(): ?array {
        return $this->performOperation('readAllExtra', EExtra::class);
    }


    //DEMO DELLA FOUNDATION PAYMENT
    public function createPayment(EPayment $payment): ?int {
        return $this->performOperation('createPayment', FPayment::class, $payment);
    }

    public function readPayment(int $idPayment): EPayment {
        return $this->performOperation('readPayment', EPayment::class, $idPayment);
    }

    public function 
































    /**
     * Stores an object in the database.
     *
     * @param object $obj The object to store.
     * @return int The ID of the stored object.
     * @throws Exception If the class or method is not found or if an error occurs during the store operation.
     */
    public function store(object $obj): int
    {
        return $this->performOperation('store', $this->getClassName($obj), $obj);
    }

    /**
     * Deletes an object from the database.
     *
     * @param int $id The ID of the object to delete.
     * @param string $fClass The associated foundation class name.
     * @return bool True if the deletion was successful, false otherwise.
     * @throws Exception If the class or method is not found or if an error occurs during the delete operation.
     */
    public function delete(int $id, string $fClass): bool
    {
        return $this->performOperation('delete', $fClass, $id);
    }

    /**
     * Loads an object from the database.
     *
     * @param int $id The ID of the object to load.
     * @param string $fClass The associated foundation class name.
     * @return object|null The loaded object, or null if not found.
     * @throws Exception If the class or method is not found.
     */
    public function load(int $id, string $fClass): ?object
    {
        return $this->performOperation('load', $fClass, $id);
    }

    /**
     * Loads a list of objects from the database.
     *
     * @param string $fClass The associated foundation class name.
     * @return array A list of objects.
     * @throws Exception If the class or method is not found.
     */
    public function loadAll(string $fClass): array
    {
        return $this->performOperation('loadAll', $fClass);
    }

    /**
     * Updates an object in the database.
     *
     * @param object $obj The object to update.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If the class or method is not found or if an error occurs during the update operation.
     */
    public function update(object $obj): bool
    {
        return $this->performOperation('update', $this->getClassName($obj), $obj);
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
     * Loads a payment from the database for a specific reservation.
     *
     * @param int $reservationId The ID of the reservation associated with the payment.
     * @return EPayment|null The EPayment object if found, otherwise null.
     * @throws Exception If an error occurs during the load operation.
     */
    public function loadPayment(int $reservationId): ?EPayment
    {
        return $this->performOperation('loadPaymentByReservation', FPayment::class, $reservationId);
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
            EAddress::class => FAddress::class,
            EAdmin::class => FAdmin::class,
            EAssistanceTicket::class => FAssistanceTicket::class,
            ECreditCard::class => FCreditCard::class,
            EEventType::class => FEvent::class,
            EExtra::class => FExtra::class,
            ELocation::class => FLocation::class,
            EOwner::class => FOwner::class,
            EPayment::class => FPayment::class,
            EReservation::class => FReservation::class,
            EReview::class => FReview::class,
            ERoom::class => FRoom::class,
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

        return $className::$method(...$params);
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
