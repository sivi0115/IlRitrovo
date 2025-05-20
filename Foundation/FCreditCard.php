<?php

namespace Foundation;

use DateTime;
use Entity\ECreditCard;
use Exception;

/**
 * Class to manage credit card operations in the database.
 */
class FCreditCard {

    /**
     * Name of the table associated with the credit card entity in the database.
     */
    protected const TABLE_NAME = 'creditcard';

    // Error messages centralized for consistency
    protected const ERR_MISSING_FIELD= 'Missing required field:';
    protected const ERR_INVALID_CVV= 'Invalid CVV for card type: ';
    protected const ERR_INVALID_EXPIRATION='Invalid expiration date.';
    protected const ERR_CARD_EXISTS = 'The credit card already exists for this user.';
    protected const  ERR_INSERTION_FAILED = 'Error during the insertion of the credit card.';
    protected const ERR_RETRIVE_CARD='Failed to retrive the inserted credit card.';
    protected const  ERR_CARD_NOT_FOUND = 'The credit card does not exist for this user.';
    protected const  ERR_UPDATE_FAILED = 'Error during the update operation.';
    protected const ERR_ID_MISSING= 'Missing required field: idCreditCard in database result.';
    protected const  ERR_SET_DEFAULT_FAILED = 'Error during the default card reset or update.';
    protected const ERR_LOADING= 'Error loading credit cards: ';
    protected const ERR_DEFAULT_CARD= 'Error setting default card: ';

    /**
     * Creates an instance of ECreditCard from the given data.
     *
     * @param array $data The data array containing credit card information.
     * @return ECreditCard The created ECreditCard object.
     * @throws Exception If required fields are missing.
     */
    public function arrayToEntity(array $data): ECreditCard {
        $requiredFields = ['idCreditCard', 'idUser', 'number', 'expiration', 'cvv', 'type', 'holder'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception(self::ERR_MISSING_FIELD . $field);
            }
        }
        return new ECreditCard(
            $data['idCreditCard'],
            $data['idUser'],
            $data['number'],
            new DateTime($data['expiration']),
            $data['cvv'],
            $data['type'],
            $data['holder']
        );
    }

    /**
     * Converts an ECreditCard object into an associative array for the database.
     *
     * @param ECreditCard $creditCard The credit card object to convert.
     * @return array The credit card data as an array.
     */
    public function entityToArray(ECreditCard $creditCard): array {
        return [
            'idCreditCard' => $creditCard->getIdCreditCard(),
            'idUser' => $creditCard->getIdUser(),
            'number' => $creditCard->getNumber(),
            'expiration' => $creditCard->getExpiration(),
            'cvv' => $creditCard->getCvv(),
            'type' => $creditCard->getType(),
            'holder' => $creditCard->getHolder()
        ];
    }

    /**
     * Create an ECreditCard object in the database.
     *
     * @param ECreditCard $creditCard The ECreditCard object to store.
     * @param int $userId The ID of the user associated with the credit card.
     * @return bool True if the operation was successful, otherwise False.
     * @throws Exception If there is an error during the store operation.
     */
    public function create(ECreditCard $creditCard): int {
        $db = FDatabase::getInstance();
        // Preliminary checks
        if (!self::isValidCVV($creditCard->getCvv(), $creditCard->getType())) {
            throw new Exception(self::ERR_INVALID_CVV . $creditCard->getType());
        }
        if (!self::isValidExpirationDate($creditCard->getExpiration())) {
            throw new Exception(self::ERR_INVALID_EXPIRATION);
        }
        if (self::exists($creditCard->getNumber(), $creditCard->getIdUser())) {
            throw new Exception(self::ERR_CARD_EXISTS);
        }
        $data = $this->entityToArray($creditCard);
        try {
            //Card insertion
            $result = $db->insert(self::TABLE_NAME, $data);
            if ($result === null) {
                throw new Exception(self::ERR_INSERTION_FAILED);
            }
            //Retrive the inserted card by number to get the assigned idCreditCard
            $storedCard = $db->load(self::TABLE_NAME, 'number', $creditCard->getNumber());
            if ($storedCard === null) {
                throw new Exception(self::ERR_RETRIVE_CARD);
            }
            //Assign the retrieved ID to the object
            $creditCard->setIdCreditCard($storedCard['idCreditCard']);
            //Return the user id associated with this card
            return $storedCard['idUser'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Reads a specific credit card from the database by IDcard.
     *
     * @param int $idCreditCard The ID of the Card to read.
     * @return ECreditCard|null The CreditCard object if found, null otherwise.
     */
    public function read(int $idCreditCard): ?ECreditCard {
        $db=FDatabase::getInstance();
        $result=$db->load(self::TABLE_NAME, 'idCreditCard', $idCreditCard);
        return $result ? $this->arrayToEntity($result): null;
    }

    /**
     * Updates an ECreditCard object in the database.
     *
     * @param ECreditCard $creditCard The ECreditCard object to update.
     * @param int $userId The ID of the user associated with the credit card.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If there is an error during the update operation.
     */
    public static function update(ECreditCard $creditCard): bool {
        $db = FDatabase::getInstance();
        if (!self::exists($creditCard->getNumber(), $creditCard->getIdUser())) {
            throw new Exception(self::ERR_CARD_NOT_FOUND);
        }
        $data = [
            'cvv' => $creditCard->getCvv(),
            'expiration' => $creditCard->getExpiration(),
            'holder' => $creditCard->getHolder(),
            'type' => $creditCard->getType(),
        ];
        if (!$db->update(self::TABLE_NAME, $data, ['idCreditCard' => $creditCard->getIdCreditCard()])) {
            throw new Exception(self::ERR_UPDATE_FAILED);
        }
        return true;
    }

    /**
     * Deletes a credit card from the database.
     *
     * @param string $numberCard The number of the credit card to delete.
     * @param int $userId The ID of the user associated with the credit card.
     * @return bool True if the credit card was successfully deleted, otherwise False.
     * @throws Exception If there is an error during the delete operation.
     */
    public static function delete(int $idCreditCard): bool {
        $db=FDatabase::getInstance();
        return $db->delete(self::TABLE_NAME, ['idCreditCard' => $idCreditCard]);
    }

    /**
     * Checks if a credit card exists in the database for the given user.
     *
     * @param string $numberCard The number of the credit card.
     * @param int $userId The ID of the user.
     * @return bool True if the credit card exists, otherwise False.
     * @throws Exception If there is an error during the check operation.
     */
    public static function exists(string $numberCard, int $userId): bool {
        $db = FDatabase::getInstance();
        return $db->exists(self::TABLE_NAME, ['number' => $numberCard, 'idUser' => $userId]);
    }

    /**
     * Loads an ECreditCard object from the database.
     *
     * @param string $number The number of the credit card to load.
     * @param int $userId The ID of the user associated with the credit card.
     * @return ECreditCard|null The loaded ECreditCard object, or null if not found.
     * @throws Exception If there is an error during the load operation.
     */
    public static function loadCreditCard(string $number, int $userId): ?ECreditCard {
        $db = FDatabase::getInstance();
        // Use the load method with a field and a value
        $cardData = $db->load(self::TABLE_NAME, 'number', $number);
        // Check if the user is valid
        if (!$cardData || $cardData['idUser'] != $userId) {
            throw new Exception(self::ERR_CARD_NOT_FOUND);
        }
        // Ensure that the result includes the 'idCreditCard' field
        if (!isset($cardData['idCreditCard'])) {
            throw new Exception(self::ERR_ID_MISSING);
        }
        // Create and return the ECreditCard object
        return self::arrayToEntity($cardData);
    }

    /**
     * Loads credit cards by user ID.
     *
     * @param int $idUser The ID of the user.
     * @return ECreditCard[] An array of ECreditCard objects.
     * @throws Exception If an error occurs during the loading of credit cards.
     */
    public static function loadCreditCardsByUser(int $idUser): array {
        $db = FDatabase::getInstance();
        try {
            $results = $db->fetchWhere(self::TABLE_NAME, ['idUser' => $idUser]);
            return array_map(fn($row) => self::arrayToEntity($row), $results);

        } catch (Exception $e) {
            throw new Exception(self::ERR_LOADING . $e->getMessage());
        }
    }

    /**
     * Masks a credit card number, showing only the last 4 digits.
     *
     * @param string $number The credit card number.
     * @return string The masked credit card number.
     */
    public static function maskCreditCardNumber(string $number): string {
        $visibleDigits = 4;
        $visiblePart = substr($number, -1 * $visibleDigits);
        $maskedPart = str_repeat('*', strlen($number) - $visibleDigits);
        return $maskedPart . $visiblePart;
    }

    /**
     * Validates the CVV (Card Verification Value).
     *
     * @param string $cvv The CVV to validate.
     * @param string $type The type of credit card.
     * @return bool True if the CVV is valid for the specified type, false otherwise.
     */
    public static function isValidCVV(string $cvv, string $type): bool {
        $type = ucwords(strtolower($type)); // Normalize the type
        return match ($type) {
            'Visa', 'Mastercard', 'Maestro', 'V-Pay', 'PagoBANCOMAT' => preg_match('/^\d{3}$/', $cvv) === 1,
            'American Express' => preg_match('/^\d{4}$/', $cvv) === 1,
            default => false,
        };
    }

    /**
     * Validates the expiration date of the credit card.
     *
     * @param string $expiration The expiration date of the credit card (format: MM/YY).
     * @return bool True if the expiration date is valid, false otherwise.
     */
    public static function isValidExpirationDate(string $expiration): bool {
        // Using preg_match to validate the format of the expiration date
        if (preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiration, $matches)) {
            $month = (int)$matches[1];
            $year = (int)$matches[2] + 2000; // Assume that the year is in the range 2000-2099
            $currentYear = (int)date('Y');
            $currentMonth = (int)date('n');
            // Check if the expiration date is in the future or the current year
            if ($year > $currentYear || ($year === $currentYear && $month >= $currentMonth)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Sets the default credit card for a user.
     *
     * @param int $idCreditCard The ID of the credit card to set as default.
     * @param int $idUser The ID of the user.
     * @return bool True if the operation was successful, false otherwise.
     * @throws Exception If an error occurs during the operation.
     */
    public static function setDefault(int $idCreditCard, int $idUser): bool {
        $db = FDatabase::getInstance();
        try {
            $db->beginTransaction();
            if (!$db->update(self::TABLE_NAME, ['isDefault' => 0], ['idUser' => $idUser])) {
                throw new Exception(self::ERR_SET_DEFAULT_FAILED);
            }
            if (!$db->update(self::TABLE_NAME, ['isDefault' => 1], ['idCreditCard' => $idCreditCard])) {
                throw new Exception(self::ERR_SET_DEFAULT_FAILED);
            }
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception(self::ERR_DEFAULT_CARD . $e->getMessage());
        }
    }
}