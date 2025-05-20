<?php

namespace Foundation;

use Entity\EPayment;
use Entity\StatoPagamento;
use Exception;

/**
 * Class to manage payment operations in the database.
 */
class FPayment {
    /**
     * Returns the name of the table associated with payments.
     *
     * @return string The name of the table.
     */
    protected const TABLE_NAME = 'payment';

    /**
     * Returns the name of the table associated with payments.
     *
     * @return string The name of the table.
     */
    public function getTableName(): string {
        return static::TABLE_NAME;
    }

    // Error messages centralized for consistency
    protected const ERR_MISSING_FIELD= 'Missing required field:';
    protected const ERR_PAYMENT_NOT_FOUND = 'The payment does not exist.';
    protected const ERR_INSERTION_FAILED = 'Error during the insertion of the payment.';
    protected const ERR_UPDATE_FAILED = 'Error during the update operation.';
    protected const ERR_DELETION_FAILED = 'Error during the deletion of the payment.';
    protected const ERR_PAYMENT_EXISTS= "Error checking payment existence: ";

    /**
     * Creates an instance of EPayment from the given data.
     *
     * @param array $data The data array containing extra information.
     * @return EPayment The created EPayment object.
     * @throws Exception If required fields are missing.
     */
    public function arrayToEntity(array $data): EPayment {
        $requiredFields = ['idPayment', 'idCreditCard', 'idReservation', 'total', 'creationTime', 'state'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception(self::ERR_MISSING_FIELD . $field);
            }
        }
        return new EPayment(
            $data['idPayment'],
            $data['idCreditCard'],
            $data['idReservation'],
            $data['total'],
            $data['creationTime'],
            $data['state']
        );
    }

    /**
     * Converts an EPayment object into an associative array for the database.
     *
     * @param EPayment $payment The payment object to convert.
     * @return array The payment data as an array.
     */
    public function entityToArray(EPayment $extra): array {
        return [
            'idPayment' => $extra->getIdPayment(),
            'idCreditCard' => $extra->getIdCreditCard(),
            'idReservation' => $extra->getIdReservation(),
            'total'=>$extra->getTotal(),
            'creationTime'=>$extra->getCreationTime(),
            'state'=>$extra->getState()
        ];
    }

    /**
     * Validates a payment state.
     *
     * @param EPayment $payment The payment object to validate.
     * @return bool True if the payment state is valid, otherwise False.
     */
    public static function validatePayment(EPayment $payment): bool {
        $validStates = [
            StatoPagamento::COMPLETATO->value,
            StatoPagamento::IN_ATTESA->value,
            StatoPagamento::FALLITO->value,
            StatoPagamento::ANNULLATO->value
        ];
        return in_array($payment->getState(), $validStates, true);
    }

    /**
     * Create an EPayment object in the database.
     *
     * @param EPayment $payment The EPayment object to store.
     * @return bool True if the operation was successful, otherwise False.
     * @throws Exception If there is an error during the store operation.
     */
    public function create(EPayment $payment): int {
        $db = FDatabase::getInstance();
        $data = $this->entityToArray($payment);
        try {
            //Payment insertion
            $result = $db->insert(self::TABLE_NAME, $data);
            if ($result === null) {
                throw new Exception(self::ERR_INSERTION_FAILED);
            }
            //Retrive the inserted payment
            $storedPayment = $db->load(self::TABLE_NAME, 'idPayment', $db->getLastInsertedId());
            if ($storedPayment === null) {
                throw new Exception(self::ERR_INSERTION_FAILED);
            }
            //Assign the retrieved ID to the object
            $payment->setIdPayment($storedPayment['idPayment']); // Usa the correct field
            return $storedPayment['idPayment'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * read a payment by its ID.
     *
     * @param int $id The ID of the payment.
     * @return EPayment|null The EPayment object or null if not found.
     * @throws Exception If there is an error during the retrieval operation.
     */
    public function read(int $idPayment): ?EPayment {
        $db=FDatabase::getInstance();
        $result=$db->load(self::TABLE_NAME, 'idPayment', $idPayment);
        return $result ? $this->arrayToEntity($result): null;
    }

    /**
     * Updates an EPayment object in the database.
     *
     * @param EPayment $payment The EPayment object to update.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If there is an error during the update operation.
     */
    public static function update(EPayment $payment): bool {
        $db = FDatabase::getInstance();
        $data = [
            'total' => $payment->getTotal(),
            'creationTime' => $payment->getCreationTime(),
            'state' => $payment->getState()
        ];
        if (!$db->update(self::TABLE_NAME, $data, ['idPayment' => $payment->getIdPayment()])) {
            throw new Exception(self::ERR_UPDATE_FAILED);
        }
        return true;
    }

    /**
     * Loads a payment by its ID using flexible conditions.
     *
     * @param array $conditions Conditions to filter the query.
     * @return EPayment The EPayment object or null if not found.
     * @throws Exception If there is an error during the operation.
     */
    public function loadPaymentByIdReservation(int $idReservation): ?EPayment {
        $db = FDatabase::getInstance();
        $result = $db -> load(self::TABLE_NAME, 'idReservation', $idReservation);
        if (empty($results)) {
            throw new Exception(self::ERR_PAYMENT_NOT_FOUND);
        }
        return $result ? $this->arrayToEntity($result): null;
    }

    /**
     * Deletes a payment from the database.
     *
     * @param int $id The ID of the payment to delete.
     * @return bool True if the payment was successfully deleted, otherwise False.
     * @throws Exception If there is an error during the delete operation.
     */
    public static function delete(int $id): bool {
        $db = FDatabase::getInstance();
        if (!$db->delete(self::TABLE_NAME, ['idPayment' => $id])) {
            throw new Exception(self::ERR_DELETION_FAILED);
        }
        return true;
    }

    /**
     * Checks if a payment exists.
     *
     * @param array $conditions Conditions to check existence.
     * @return bool True if a payment exists, otherwise False.
     */
    public static function exists(array $conditions): bool {
        $db = FDatabase::getInstance();
        try {
            return $db->exists(self::TABLE_NAME, $conditions);
        } catch (Exception $e) {
            throw new Exception(self::ERR_PAYMENT_EXISTS . $e->getMessage());
        }
    }
    
    /**
     * Calculates the total amount of all payments matching the conditions.
     *
     * @param array $conditions Conditions to filter payments.
     * @return float The total amount.
     */
    public static function getTotalPaymentsAmount(array $conditions = []): float {
        $db = FDatabase::getInstance();
        try {
            // Use the fetchWhere method to calculate the total sum
            $payments = $db->fetchWhere(self::TABLE_NAME, $conditions);
            // Sum the 'total' field from all matching rows
            $totalAmount = array_reduce($payments, fn($sum, $payment) => $sum + $payment['total'], 0);
            return $totalAmount;
        } catch (Exception $e) {
            throw new Exception("Error calculating total payments amount: " . $e->getMessage());
        }
    }
}