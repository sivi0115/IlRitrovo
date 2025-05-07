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
class FReservation
{
    protected const TABLE_NAME = 'reservation';

    private function __construct() {}

    /**
     * Creates an EReservation entity directly from provided data.
     *
     * @param array $data An associative array containing reservation details.
     * @return EReservation The created EReservation object.
     * @throws Exception If required fields are missing or invalid.
     */
    public static function createEntityReservation(array $data): EReservation
    {
        if (empty($data['creationTime']) || empty($data['state']) || empty($data['totPrice'])) {
            throw new Exception('Missing required fields to create EReservation.');
        }

        return new EReservation(
            $data['idReservation'] ?? null,
            new DateTime($data['creationTime']),
            $data['state'],
            $data['durationEvent'] ?? null,
            $data['totPrice'],
            $data['idUser'] ?? null,
            $data['idRoom'] ?? null,
            $data['idEvent'] ?? null,
            $data['idPayment'] ?? null
        );
    }

    /**
     * Stores a new reservation in the database.
     *
     * @param EReservation $reservation The reservation to store.
     * @return int The ID of the newly inserted reservation.
     * @throws Exception If an error occurred during the insertion.
     */
    public static function storeReservation(EReservation $reservation): int
    {
        $db = FDatabase::getInstance();

        // Validate user
        self::validateUser($reservation->getIdUser());

        // Validate durationEvent
        $durationEvent = self::validateDurationEvent($reservation->getDurationEvent());

        // Prepare data for insertion
        $data = [
            'creationTime' => $reservation->getCreationTime()->format('Y-m-d H:i:s'),
            'state' => $reservation->getState(),
            'durationEvent' => $durationEvent, // Store the validated string
            'totPrice' => $reservation->getTotPrice(),
            'idUser' => $reservation->getIdUser(),
            'idRoom' => $reservation->getIdRoom(),
            'idEvent' => $reservation->getIdEvent()
        ];

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
     * Updates an existing reservation in the database.
     *
     * @param EReservation $reservation The reservation to update.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If an error occurred during the update.
     */
    public static function updateReservation(EReservation $reservation): bool
    {
        $db = FDatabase::getInstance();

        // Validate durationEvent
        $durationEvent = self::validateDurationEvent($reservation->getDurationEvent());

        // Prepare data for update
        $data = [
            'creationTime' => $reservation->getCreationTime()->format('Y-m-d H:i:s'),
            'state' => $reservation->getState(),
            'durationEvent' => $durationEvent, // Store the validated string
            'totPrice' => $reservation->getTotPrice(),
            'idUser' => $reservation->getIdUser(),
            'idRoom' => $reservation->getIdRoom(),
            'idEvent' => $reservation->getIdEvent() ?: null
        ];

        // Debugging: Output data to be updated
        echo "Updating reservation data: " . json_encode($data) . "\n";

        // Update record in the database
        return $db->update(self::TABLE_NAME, $data, ['idReservation' => $reservation->getIdReservation()]);
    }

    /**
     * Loads a reservation by a specified value and column.
     *
     * @param mixed $value The value to search for.
     * @param string $column The column to search in.
     * @return EReservation|null The loaded reservation, or null if not found.
     * @throws Exception If an error occurs during the database operation.
     */
    public static function loadReservation(mixed $value, string $column): ?EReservation
    {
        $db = FDatabase::getInstance();
        $result = $db->load(self::TABLE_NAME, $column, $value);
        return $result ? self::createReservationFromRow($result) : null;
    }

    /**
     * Deletes a reservation by its ID.
     *
     * @param int $idReservation The ID of the reservation to delete.
     * @return bool True if the deletion was successful, false otherwise.
     * @throws Exception If an error occurs during the deletion.
     */
    public static function deleteReservation(int $idReservation): bool
    {
        $db = FDatabase::getInstance();
        return $db->delete(self::TABLE_NAME, ['idReservation' => $idReservation]);
    }

    /**
     * Updates the state of a reservation.
     *
     * @param int $idReservation The ID of the reservation.
     * @param string $newState The new state.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If an error occurs during the update.
     */
    private static function updateStateReservation(int $idReservation, string $newState): bool
    {
        $db = FDatabase::getInstance();

        // Aggiorna lo stato della prenotazione
        return $db->update(
            self::TABLE_NAME, // Nome della tabella
            ['state' => $newState], // Dati da aggiornare
            ['idReservation' => $idReservation] // Condizione WHERE
        );
    }

    /**
     * Approves a reservation.
     *
     * @param int $idReservation The ID of the reservation.
     * @return bool True if the approval was successful, false otherwise.
     * @throws Exception If an error occurs during the update.
     */
    public static function approveReservation(int $idReservation): bool
    {
        return self::updateStateReservation($idReservation, 'approved');
    }

    /**
     * Rejects a reservation.
     *
     * @param int $idReservation The ID of the reservation.
     * @return bool True if the rejection was successful, false otherwise.
     * @throws Exception If an error occurs during the update.
     */
    public static function rejectReservation(int $idReservation): bool
    {
        return self::updateStateReservation($idReservation, 'rejected');
    }

    /**
     * Cancels a reservation.
     *
     * @param int $idReservation The ID of the reservation.
     * @return bool True if the cancellation was successful, false otherwise.
     * @throws Exception If an error occurs during the update.
     */
    public static function cancelReservation(int $idReservation): bool
    {
        return self::updateStateReservation($idReservation, 'cancelled');
    }

    /**
     * Gets reservations for a given user ID.
     *
     * @param int $userId The ID of the user.
     * @return EReservation[] An array of reservations.
     * @throws Exception If an error occurs during the database operation.
     */
    public static function getReservationsByUserId(int $userId): array
    {
        $db = FDatabase::getInstance();
        $result = $db->fetchWhere(self::TABLE_NAME, ['idUser' => $userId]);
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
    public static function getReservationsByRoom(int $roomId): array
    {
        $db = FDatabase::getInstance();
        $result = $db->fetchWhere(self::TABLE_NAME, ['idRoom' => $roomId]);
        $reservations = [];
        foreach ($result as $row) {
            $reservations[] = self::createReservationFromRow($row);
        }
        return $reservations;
    }

    /**
     * Creates a reservation from a database row.
     *
     * @param array $row The database row.
     * @return EReservation The created reservation object.
     * @throws Exception If an error occurs during the creation of the reservation.
     */
    private static function createReservationFromRow(array $row): EReservation
    {
        return new EReservation(
            $row['idReservation'] ?? null,
            new DateTime($row['creationTime']),
            $row['state'],
            $row['durationEvent'] ?? null,
            $row['totPrice'],
            $row['idUser'] ?? null,
            $row['idRoom'] ?? null,
            $row['idEvent'] ?? null
        );
    }

    /**
     * Validates the user ID.
     *
     * @param int $userId The ID of the user to validate.
     * @throws Exception If the user does not exist or is invalid.
     */
    private static function validateUser(int $userId): void
    {
        $db = FDatabase::getInstance();

        // Controlla se l'utente esiste nel database
        $userExists = $db->exists('user', ['idUser' => $userId]);
        if (!$userExists) {
            throw new Exception('Invalid user ID provided for reservation. User does not exist.');
        }
    }


    /**
     * Gets reservations for locations owned by a specific owner.
     *
     * @param int $ownerId The ID of the owner.
     * @return EReservation[] An array of reservations related to the owner's locations.
     * @throws Exception If an error occurs during the database operation.
     */
    public static function getReservationsByOwner(int $ownerId): array
    {
        $db = FDatabase::getInstance();

        // Ottieni tutte le location dell'owner
        $locations = $db->fetchWhere('location', ['idOwner' => $ownerId]);

        // Estrai gli id delle location
        $locationIds = array_column($locations, 'idLocation');

        if (empty($locationIds)) {
            return []; // Nessuna location trovata
        }

        // Ottieni tutte le stanze collegate alle location dell'owner
        $rooms = $db->fetchWhere('room', ['idLocation' => $locationIds]);

        // Estrai gli id delle stanze
        $roomIds = array_column($rooms, 'idRoom');

        if (empty($roomIds)) {
            return []; // Nessuna stanza trovata
        }

        // Ottieni tutte le prenotazioni collegate alle stanze
        $reservationsData = $db->fetchWhere('reservation', ['idRoom' => $roomIds]);

        // Trasforma i risultati in oggetti EReservation
        $reservations = [];
        foreach ($reservationsData as $row) {
            $reservations[] = self::createReservationFromRow($row);
        }

        return $reservations;
    }



    /**
     * Validates the durationEvent.
     *
     * @param string $durationEvent
     * @return string
     * @throws Exception
     */
    private static function validateDurationEvent(string $durationEvent): string
    {
        if (empty($durationEvent)) {
            throw new Exception('Invalid duration of the event. Duration must be a non-empty string.');
        }
        return $durationEvent;
    }

}
