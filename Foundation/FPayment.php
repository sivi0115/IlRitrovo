<?php

namespace Foundation;

use Entity\EPayment;
use Entity\StatoPagamento;
use Exception;

/**
 * Class to manage payment operations in the database.
 */
class FPayment {
    protected const TABLE_NAME = 'payment';

    // Error messages centralized for consistency
    protected const ERR_PAYMENT_NOT_FOUND = 'The payment does not exist.';
    protected const ERR_INSERTION_FAILED = 'Error during the insertion of the payment.';
    protected const ERR_UPDATE_FAILED = 'Error during the update operation.';
    protected const ERR_DELETION_FAILED = 'Error during the deletion of the payment.';

    /**
     * Creates an instance of EPayment from the given data.
     *
     * @param array $data The data array containing payment information.
     * @return EPayment The created EPayment object.
     * @throws Exception If required fields are missing.
     */
    public static function createEntityPayment(array $data): EPayment {
        $requiredFields = ['idPayment', 'idCreditCard', 'idReservation', , 'total', 'creationTime', 'state'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
        return new EPayment(
            $data['idPayment'],
            $data['idCreditCard'],
            $data['idReservation'],
            $data['total'],
            new \DateTime($data['creationTime']),
            $data['state'],
        );
    }

    /**
     * Create an EPayment object in the database.
     *
     * @param EPayment $payment The EPayment object to store.
     * @return bool True if the operation was successful, otherwise False.
     * @throws Exception If there is an error during the store operation.
     */
    public static function createPayment(EPayment $payment): bool {
        $db = FDatabase::getInstance();
        $data = [
            'total' => $payment->getTotal(),
            'state' => $payment->getState(),
            'idCreditCard' => $payment->getIdCreditCard(),
            'idReservation' => $payment->getIdReservation(),
            'creationTime' => $payment->getCreationTime()
        ];
        $result = $db->insert(self::TABLE_NAME, $data);
        if ($result === null) {
            throw new Exception(self::ERR_INSERTION_FAILED);
        }
        $storedPayment = $db->load(self::TABLE_NAME, 'idPayment', $db->getLastInsertedId());
        if ($storedPayment === null) {
            throw new Exception("Failed to retrieve the inserted payment.");
        }
        $payment->setIdPayment($storedPayment['idPayment']); // Usa il campo corretto
        return true;
    }

    /**
     * read a payment by its ID.
     *
     * @param int $id The ID of the payment.
     * @return EPayment|null The EPayment object or null if not found.
     * @throws Exception If there is an error during the retrieval operation.
     */
    public static function readPayment(int $id): ?EPayment {
        $db = FDatabase::getInstance();
        $paymentData = $db->load(self::TABLE_NAME, 'idPayment', $id);
        if ($paymentData === null) {
            throw new Exception(self::ERR_PAYMENT_NOT_FOUND);
        }
        return self::createEntityPayment($paymentData);
    }

    /**
     * Updates an EPayment object in the database.
     *
     * @param EPayment $payment The EPayment object to update.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If there is an error during the update operation.
     */
    public static function updatePayment(EPayment $payment): bool {
        $db = FDatabase::getInstance();
        $data = [
            'total' => $payment->getTotal(),
            'state' => $payment->getState(),
            'idCreditCard' => $payment->getIdCreditCard(),
            'idReservation' => $payment->getIdReservation(),
            'creationTime' => $payment->getCreationTime()
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
     * @return EPayment|null The EPayment object or null if not found.
     * @throws Exception If there is an error during the operation.
     */
    public static function loadPaymentByIdReservation(int $idReservation): ?EPayment {
        $db = FDatabase::getInstance();
        $conditions = [self::TABLE_NAME, 'idReservation' => $idReservation];
        $result = $db -> load(self::TABLE_NAME, 'idReservation', $idReservation);
        if (empty($result)) {
            throw new Exception(self::ERR_PAYMENT_NOT_FOUND);
        }
        return $result ? null;
    }

    public static function loadPayment(array $conditions): ?EPayment {
        $db = FDatabase::getInstance();
        $result = $db->fetchWhere(self::TABLE_NAME, $conditions);
        if (empty($result)) {
            throw new Exception(self::ERR_PAYMENT_NOT_FOUND);
        }
        return self::createEntityPayment($result[0]);
    }

    /**
     * Retrieves all payments.
     *
     * @return EPayment[] An array of EPayment objects.
     * @throws Exception If there is an error during the retrieval operation.
     */
    public static function loadAllPayments(): array {
        $db = FDatabase::getInstance();
        try {
            $results = $db->fetchAllFromTable(self::TABLE_NAME); // Recupera tutti i record senza query SQL esplicita
            return array_map(fn($row) => self::createEntityPayment($row), $results);
        } catch (Exception $e) {
            throw new Exception("Error loading payments: " . $e->getMessage());
        }
    }

    /**
     * Loads all payments that match given conditions.
     *
     * @param array $conditions Conditions to filter the query.
     * @return EPayment[] An array of EPayment objects.
     * @throws Exception If there is an error during the operation.
     */
    public static function loadAllPaymentBy(array $conditions): array {
        $db = FDatabase::getInstance();
        try {
            $results = $db->fetchWhere(self::TABLE_NAME, $conditions);
            return array_map(fn($row) => self::createEntityPayment($row), $results);
        } catch (Exception $e) {
            throw new Exception("Error loading payments with conditions: " . $e->getMessage());
        }
    }

    /**
     * Checks if a payment exists.
     *
     * @param array $conditions Conditions to check existence.
     * @return bool True if a payment exists, otherwise False.
     */
    public static function existsPayment(array $conditions): bool {
        $db = FDatabase::getInstance();
        try {
            return $db->exists(self::TABLE_NAME, $conditions);
        } catch (Exception $e) {
            throw new Exception("Error checking payment existence: " . $e->getMessage());
        }
    }

    /**
     * Deletes a payment from the database.
     *
     * @param int $id The ID of the payment to delete.
     * @return bool True if the payment was successfully deleted, otherwise False.
     * @throws Exception If there is an error during the delete operation.
     */
    public static function deletePayment(int $id): bool {
        $db = FDatabase::getInstance();
        if (!$db->delete(self::TABLE_NAME, ['idPayment' => $id])) {
            throw new Exception(self::ERR_DELETION_FAILED);
        }
        return true;
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

    /**
     * Loads all payments associated with a specific user.
     *
     * @param int $idReservation The ID of the reservation.
     * @return EPayment An EPayment objects.
     * @throws Exception If there is an error during the operation.
     */
    public static function loadPaymentByUser(int $idUser): array {
        $db = FDatabase::getInstance();
        try {
            // Define the conditions for the fetchWhere method
            $conditions = ['idUser' => $idUser];
            // Use fetchWhere to retrieve payments joined with creditcard by idUser
            $results = $db->fetchWhere(
                self::TABLE_NAME . " AS p INNER JOIN creditcard AS c ON p.idCreditCard = c.idCreditCard",
                $conditions
            );
            if (empty($results)) {
                throw new Exception(self::ERR_PAYMENT_NOT_FOUND);
            }
            // Convert the results into EPayment objects
            return array_map(fn($row) => self::createEntityPayment($row), $results);
        } catch (Exception $e) {
            throw new Exception("Error loading payments by user: " . $e->getMessage());
        }
    }
}