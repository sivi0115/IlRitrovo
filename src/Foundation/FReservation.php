<?php

namespace Foundation;

use DateTime;
use Entity\EReservation;
use Exception;

/**
 * Class FReservation represents a reservation entity.
 *
 * @package Foundation
 */
class FReservation {
    /**
     * Name of the table associated with the reservation entity in the database.
     */
    protected const TABLE_NAME = 'reservation';

    /**
     * Returns the name of the table associated with extras.
     *
     * @return string The name of the table.
     */
    public function getTableName(): string {
        return static::TABLE_NAME;
    }

    // Error messages centralized for consistency
    protected const ERR_MISSING_FIELD= 'Missing required field:';

    /**
     * Creates an EReservation entity directly from provided data.
     *
     * @param array $data An associative array containing reservation details.
     * @return EReservation The created EReservation object.
     * @throws Exception If required fields are missing or invalid.
     */
    public function arrayToEntity(array $data): EReservation {
        $requiredFields = ['reservationDate', 'timeFrame', 'state', 'totPrice', 'people'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception(self::ERR_MISSING_FIELD . $field);
            }
        }
        return new EReservation(
            $data['idReservation'] ?? null,
            $data['idUser'] ?? null,
            $data['idTable'] ?? null,
            $data['idRoom'] ?? null,
            $data['creationTime'] ?? null,
            $data['reservationTime'] ?? null,
            $data['timeFrame'] ?? null,
            $data['state'] ?? null,
            $data['totPrice'] ?? null,
            $data['people'] ?? null,
            $data['comment'] ?? null,
        );
    }

    /**
     * Converts an EReservation object into an associative array for the database.
     *
     * @param EReservation $reservation The review object to convert.
     * @return array The reservation data as an array.
     */
    public function reservationToArray(EReservation $reservation): array {
        return [
            'idReservation' => $reservation->getIdReservation(),
            'idUser' => $reservation->getIdUser(),
            'idTable' => $reservation->getIdTable(),
            'idRoom' => $reservation->getIdRoom(),
            'creationTime' => $reservation->getCreationTime(),
            'reservationTime' => $reservation->getReservationDate(),
            'timeFrame' => $reservation->getReservationTimeFrame(),
            'state' => $reservation->getState(),
            'totPrice' => $reservation->getTotPrice(),
            'people' => $reservation->getPeople(),
            'comment' => $reservation->getComment()
        ];
    }

    /**
     * Create a new reservation in the database.
     *
     * @param EReservation $reservation The reservation to store.
     * @return int The ID of the newly inserted reservation.
     * @throws Exception If an error occurred during the insertion.
     */
    public function create(EReservation $reservation): int {
        $db = FDatabase::getInstance();
        // Validate user
        self::validateUser($reservation->getIdUser());
        // Validate durationEvent
        $durationEvent = self::validateTimeFrame($reservation->getReservationTimeFrame());
        // Prepare data for insertion
        $data = $this->reservationToArray($reservation);
        // Debugging: Output data to be inserted
        echo "Inserting reservation data: " . json_encode($data) . "\n";
        // Insert record into the database
        $id = $db->insert(self::TABLE_NAME, $data);
        if ($id === null) {
            throw new Exception('Error during the insertion of the reservation.');
        }
        return $id;
    }

    /**
     * Reads a reservation from the database by their ID.
     *
     * @param int $id The ID of the reservation to read.
     * @return EReservation|null The reservation object if found, null otherwise.
     * @throws Exception
     */
    public function read(int $id): ?EReservation {
        $db=FDatabase::getInstance();
        $result=$db->load(static::TABLE_NAME, 'idReservation', $id);
        return $result ? $this->createEntityReservation($result) : null;
    }

    /**
     * Updates an existing reservation in the database.
     *
     * @param EReservation $reservation The reservation to update.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If an error occurred during the update.
     */
    public function update(EReservation $reservation): bool {
        $db = FDatabase::getInstance();
        // Validate durationEvent
        $durationEvent = self::validateTimeFrame($reservation->getReservationTimeFrame());
        // Prepare data for update
        $data = $this->reservationToArray($reservation);
        // Debugging: Output data to be updated
        echo "Updating reservation data: " . json_encode($data) . "\n";
        // Update record in the database
        return $db->update(self::TABLE_NAME, $data, ['idReservation' => $reservation->getIdReservation()]);
    }

    /**
     * Deletes a reservation by its ID.
     *
     * @param int $idReservation The ID of the reservation to delete.
     * @return bool True if the deletion was successful, false otherwise.
     * @throws Exception If an error occurs during the deletion.
     */
    public function delete(int $idReservation): bool {
        $db = FDatabase::getInstance();
        return $db->delete(self::TABLE_NAME, ['idReservation' => $idReservation]);
    }

    /**
     * Creates a reservation from a database row.
     *
     * @param array $row The database row.
     * @return EReservation The created reservation object.
     * @throws Exception If an error occurs during the creation of the reservation.
     */
    private static function createReservationFromRow(array $row): EReservation {
        return new EReservation(
            $row['idReservation'] ?? null,
            $row['idUser'] ?? null,
            $row['idTable'] ?? null,
            $row['idRoom'] ?? null,
            $row['creationTime'] ?? null,
            $row['reservationTime'] ?? null,
            $row['timeFrame'] ?? null,
            $row['state'],
            $row['totPrice'] ?? null,
            $row['people'] ?? null,
            $row['comment'] ?? null,
        );
    }

    /**
     * Loads a reservation by a specified value and column.
     *
     * @param mixed $value The value to search for.
     * @param string $column The column to search in.
     * @return EReservation|null The loaded reservation, or null if not found.
     * @throws Exception If an error occurs during the database operation.
     */
    public static function loadReservation(mixed $value, string $column): ?EReservation {
        $db = FDatabase::getInstance();
        $result = $db->load(self::TABLE_NAME, $column, $value);
        return $result ? self::createReservationFromRow($result) : null;
    }

    /**
     * Gets reservations for a given user ID.
     *
     * @param int $userId The ID of the user.
     * @return EReservation[] An array of reservations.
     * @throws Exception If an error occurs during the database operation.
     */
    public static function getReservationsByUserId(int $userId): array {
        $db = FDatabase::getInstance();
        $result = $db->fetchWhere(self::TABLE_NAME, ['idUser' => $userId]);
        $reservations = [];
        foreach ($result as $row) {
            $reservations[] = self::createReservationFromRow($row);
        }
        return $reservations;
    }

    /**
     * Gets reservations for a specific table.
     *
     * @param int $idTable The ID of the table.
     * @return EReservation[] An array of EReservation objects associated with the table.
     * @throws Exception If there is an error during the retrieval operation.
     */
    public static function getReservationsByTable(int $tableId): array {
        $db = FDatabase::getInstance();
        $result = $db->fetchWhere(self::TABLE_NAME, ['idTable' => $tableId]);
        $reservations = [];
        foreach ($result as $row) {
            $reservations[] = self::createReservationFromRow($row);
        }
        return $reservations;
    }

    /**
     * Gets reservations for a specific room.
     *
     * @param int $roomId The ID of the room.
     * @return EReservation[] An array of EReservation objects associated with the room.
     * @throws Exception If there is an error during the retrieval operation.
     */
    public static function getReservationsByRoom(int $roomId): array {
        $db = FDatabase::getInstance();
        $result = $db->fetchWhere(self::TABLE_NAME, ['idRoom' => $roomId]);
        $reservations = [];
        foreach ($result as $row) {
            $reservations[] = self::createReservationFromRow($row);
        }
        return $reservations;
    }

    /**
     * Updates the state of a reservation.
     *
     * @param int $idReservation The ID of the reservation.
     * @param string $newState The new state.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If an error occurs during the update.
     */
    private static function updateStateReservation(int $idReservation, string $newState): bool {
        $db = FDatabase::getInstance();
        // Update the reservation status
        return $db->update(
            self::TABLE_NAME, // Table name
            ['state' => $newState], // Data to update
            ['idReservation' => $idReservation] // WHERE condition
        );
    }

    /**
     * Approves a reservation.
     *
     * @param int $idReservation The ID of the reservation.
     * @return bool True if the approval was successful, false otherwise.
     * @throws Exception If an error occurs during the update.
     */
    public static function approveReservation(int $idReservation): bool {
        return self::updateStateReservation($idReservation, 'approved');
    }

    /**
     * Rejects a reservation.
     *
     * @param int $idReservation The ID of the reservation.
     * @return bool True if the rejection was successful, false otherwise.
     * @throws Exception If an error occurs during the update.
     */
    public static function rejectReservation(int $idReservation): bool {
        return self::updateStateReservation($idReservation, 'rejected');
    }

    /**
     * Cancels a reservation.
     *
     * @param int $idReservation The ID of the reservation.
     * @return bool True if the cancellation was successful, false otherwise.
     * @throws Exception If an error occurs during the update.
     */
    public static function cancelReservation(int $idReservation): bool {
        return self::updateStateReservation($idReservation, 'cancelled');
    }

    /**
     * Validates the user ID.
     *
     * @param int $userId The ID of the user to validate.
     * @throws Exception If the user does not exist or is invalid.
     */
    private static function validateUser(int $userId): void {
        $db = FDatabase::getInstance();

        // Controlla se l'utente esiste nel database
        $userExists = $db->exists('user', ['idUser' => $userId]);
        if (!$userExists) {
            throw new Exception('Invalid user ID provided for reservation. User does not exist.');
        }
    }

    /**
     * Validates the durationEvent.
     *
     * @param string $timeFrame
     * @return string
     * @throws Exception
     */
    private static function validateTimeFrame(string $timeFrame): string {
        if (empty($timeFrame)) {
            throw new Exception('Invalid time frame. Duration must be a non-empty string.');
        }
        return $timeFrame;
    }
}