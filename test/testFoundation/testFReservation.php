<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Entity\ELocation;
use Foundation\FDatabase;
use Foundation\FReservation;
use Entity\EReservation;
use Entity\EUser;
use Entity\ERoom;

/**
 * Inserts a test owner into the database.
 *
 * @return int The ID of the inserted owner.
 * @throws Exception If insertion fails.
 */
function insertTestOwner(): int
{
    $db = FDatabase::getInstance();
    $ownerData = [
        'username' => 'testuser',
        'name' => 'Test',
        'surname' => 'Owner',
        'birthDate' => '2000-01-01',
        'phone' => '1234567890',
        'image' => 'https://example.com/image.jpg',
        'email' => 'testuser@example.com',
        'password' => password_hash('securepassword', PASSWORD_DEFAULT),
        'validation' => 0,
    ];

    $ownerId = $db->insert('owner', $ownerData);
    if ($ownerId === null) {
        throw new Exception('Errore: impossibile inserire il proprietario di test.');
    }

    return $ownerId;
}

/**
 * Inserts a test address into the database.
 *
 * @return int The ID of the inserted address.
 * @throws Exception If insertion fails.
 */
function insertTestAddress(): int
{
    $db = FDatabase::getInstance();
    $addressData = [
        'streetAddress' => '123 Test Street',
        'civicNumber' => '10',
        'city' => 'Test City',
        'postalCode' => '12345',
        'country' => 'Test Country',
        'state' => 'Test State',
    ];

    $addressId = $db->insert('address', $addressData);
    if ($addressId === null) {
        throw new Exception('Errore: impossibile inserire l\'indirizzo di test.');
    }

    return $addressId;
}

/**
 * Inserts a test user into the database.
 *
 * @return int The ID of the inserted user.
 * @throws Exception If insertion fails.
 */
function insertTestUser(): int
{
    $db = FDatabase::getInstance();
    $testUser = new EUser(
        null, // ID generated automatically, so null
        'test_user1', // username
        'Test', // name
        'User', // surname
        new DateTime('1990-01-01'), // birthDate
        '9876543210', // phone
        'https://example.com/user_image.jpg', // image
        'testuser@example.com', // email
        'secureUserPassword@123', // password
        0, // ban
        null // motivation
    );

    $data = [
        'username' => $testUser->getUsername(),
        'name' => $testUser->getName(),
        'surname' => $testUser->getSurname(),
        'birthDate' => $testUser->getBirthDate()->format('Y-m-d'),
        'phone' => $testUser->getPhone(),
        'image' => $testUser->getImage(),
        'email' => $testUser->getEmail(),
        'password' => password_hash($testUser->getPassword(), PASSWORD_DEFAULT),
        'ban' => $testUser->getBan() ? 1 : 0,
        'motivation' => $testUser->getMotivation(),
    ];

    $userId = $db->insert('user', $data);
    if ($userId === null) {
        throw new Exception('Errore durante l\'inserimento dell\'user.');
    }

    return $userId;
}

/**
 * Inserts a test location into the database.
 *
 * @return int The ID of the inserted location.
 * @throws Exception If insertion fails.
 */
function insertTestLocation(): int
{
    $db = FDatabase::getInstance();
    $locationData = [
        'name' => 'Test Location',
        'VATNumber' => '12345678901',
        'description' => 'A test location for reviews.',
        'type' => 'Restaurant',
        'idOwner' => insertTestOwner(), // Insert test owner and use their ID
        'idAddress' => insertTestAddress(), // Insert a test address and use its ID
        'status' => 'Active',
        'views' => 0,
        'created_at' => (new DateTime())->format('Y-m-d H:i:s')
    ];

    $locationId = $db->insert('location', $locationData);
    if ($locationId === null) {
        throw new Exception('Errore durante l\'inserimento della location.');
    }

    return $locationId;
}

/**
 * Inserts a test room into the database.
 *
 * @param int $locationId
 * @return int The ID of the inserted room.
 * @throws Exception If insertion fails.
 */
function insertTestRoom(int $locationId): int
{
    $db = FDatabase::getInstance();
    $roomData = [
        'name' => 'Test Room',
        'capacity' => 50,
        'squareFootage' => 100.0,
        'photo' => 'https://example.com/room_photo.jpg',
        'price' => 200.0,
        'idLocation' => $locationId,
    ];

    $roomId = $db->insert('room', $roomData);
    if ($roomId === null) {
        throw new Exception('Errore durante l\'inserimento della stanza.');
    }

    return $roomId;
}

/**
 * Inserts a test reservation into the database.
 *
 * @param int $userId
 * @param int $roomId
 * @return int The ID of the inserted reservation.
 * @throws Exception If insertion fails.
 */
function insertTestReservation(int $userId, int $roomId): int
{
    $db = FDatabase::getInstance();
    $reservationData = [
        'creationTime' => (new DateTime())->format('Y-m-d H:i:s'),
        'state' => 'pending',
        'durationEvent' => '2 hours',
        'totPrice' => 100.0,
        'idUser' => $userId,
        'idRoom' => $roomId,
        'idEvent' => 3, // Replace with actual event ID if necessary
    ];

    $reservationId = $db->insert('reservation', $reservationData);
    if ($reservationId === null) {
        throw new Exception('Errore durante l\'inserimento della prenotazione.');
    }

    return $reservationId;
}

/**
 * Tests the reservation functionalities.
 */
function testReservationFlow()
{
    echo "Testing reservation flow...\n";

    try {
        // Insert test data
        $userId = insertTestUser();
        $locationId = insertTestLocation();
        $roomId = insertTestRoom($locationId);

        // Insert reservation
        $reservationId = insertTestReservation($userId, $roomId);
        echo "Inserted reservation with ID: $reservationId\n";

        // Load reservation
        $reservation = FReservation::loadReservation($reservationId, 'idReservation');
        echo "Loaded reservation: \n";
        print_r($reservation);

        // Update reservation
        $reservation->setState('approved');
        $updateResult = FReservation::updateReservation($reservation);
        echo "Reservation updated: " . ($updateResult ? 'true' : 'false') . "\n";

        // Delete reservation
        //$deleteResult = FReservation::deleteReservation($reservationId);
        //echo "Reservation deleted: " . ($deleteResult ? 'true' : 'false') . "\n";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

testReservationFlow();
