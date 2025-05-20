<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FRoom;
use Foundation\FLocation; // Add the location class
use Entity\ERoom;
use Entity\ELocation;
use Entity\EOwner;
use Entity\EAddress;

function getTestOwnerData(): EOwner
{
    return new EOwner(
        null, // ID will be set on insert
        'testuser_',
        'Test',
        'Owner',
        new DateTime('2000-01-01'), // Birthdate
        '1234567890', // Phone
        'https://example.com/image.jpg', // Image URL
        'testowner@example.com', // Email
        'securePassword@123', // Password
        0 // Not banned
    );
}

/**
 * @throws Exception
 */
function insertTestOwner(): int
{
    $db = FDatabase::getInstance();
    $testOwner = getTestOwnerData();

    $data = [
        'username' => $testOwner->getUsername(),
        'name' => $testOwner->getName(),
        'surname' => $testOwner->getSurname(),
        'birthDate' => $testOwner->getBirthDate()->format('Y-m-d'),
        'phone' => $testOwner->getPhone(),
        'image' => $testOwner->getImage(),
        'email' => $testOwner->getEmail(),
        'password' => password_hash($testOwner->getPassword(), PASSWORD_DEFAULT),
        'validation' => $testOwner->getValidation()
    ];

    $ownerId = $db->insert('owner', $data);
    if ($ownerId === null) {
        throw new Exception('Error inserting the owner.');
    }

    return $ownerId;
}

function getTestAddressData(): EAddress
{
    return new EAddress(
        '123 Test Street', // Street Address
        '10', // Civic Number
        'Test City', // City
        '12345', // Postal Code
        'Test Country', // Country
        'Test State', // State
        null
    );
}

/**
 * @throws Exception
 */
function insertTestAddress(): int
{
    $db = FDatabase::getInstance();
    $testAddress = getTestAddressData();

    $data = [
        'streetAddress' => $testAddress->getStreetAddress(),
        'civicNumber' => $testAddress->getCivicNumber(),
        'city' => $testAddress->getCity(),
        'postalCode' => $testAddress->getPostalCode(),
        'country' => $testAddress->getCountry(),
        'state' => $testAddress->getState(),
    ];

    $addressId = $db->insert('address', $data);
    if ($addressId === null) {
        throw new Exception('Error inserting the address.');
    }

    return $addressId;
}

/**
 * @throws Exception
 */
function getTestLocationData(int $ownerId, int $addressId): ELocation
{
    return new ELocation(
        null, // ID will be set on insert
        'Test Location',
        'IT12345678901', // Ensure this follows the format ITxxxxxxxxxxx
        'A test location description.',
        (array)'https://example.com/image.jpg',
        'Type A', // Added type parameter for location
        $ownerId,
        $addressId
    );
}

$insertedLocationId = null; // Variable to store the inserted location ID
$insertedRoomId = null; // Variable to store the inserted room ID

function testInsertLocation(): void
{
    global $insertedLocationId;
    echo "\nTest 1: Inserting a new location\n";
    try {
        $db = FDatabase::getInstance();
        $fLocation = new FLocation();

        // Get IDs for the owner and address
        $ownerId = insertTestOwner();  // Insert owner and get ID
        $addressId = insertTestAddress();  // Insert address and get ID

        $testLocation = getTestLocationData($ownerId, $addressId);
        $insertId = $fLocation->storeLocation($testLocation);

        if ($insertId !== null) {
            $insertedLocationId = $insertId; // Save inserted ID for subsequent tests
            echo "Insert successful. Inserted ID: $insertedLocationId\n";
            echo "Test 1: PASSED\n";
        } else {
            echo "Insert failed.\n";
            echo "Test 1: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 1: FAILED\n";
    }
}

function getTestRoomData(int $locationId): ERoom
{
    return new ERoom(
        null, // ID not needed for creation
        'Test Room',
        2, // Capacity or similar field, adjust as needed
        300.0, // Price
        'https://example.com/image.jpg',
        150.00, // Size or similar field
        $locationId // Use the dynamically assigned location ID
    );
}

function testInsertRoom(): void
{
    global $insertedLocationId, $insertedRoomId;
    echo "\nTest 1: Inserting a new room\n";
    try {
        if ($insertedLocationId === null) {
            echo "Test 1: Location ID not available, insert location test must be executed first.\n";
            return;
        }

        $testRoom = getTestRoomData($insertedLocationId);
        $insertedRoomId = FRoom::storeRoom($testRoom);

        if ($insertedRoomId !== null) {
            echo "Room insertion successful. Inserted ID: $insertedRoomId\n";
            echo "Test 1: PASSED\n";
        } else {
            echo "Room insertion failed.\n";
            echo "Test 1: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 1: FAILED\n";
    }
}

function testLoadRoomById(): void
{
    global $insertedRoomId;
    echo "\nTest 2: Loading room by ID\n";
    try {
        $room = FRoom::loadRoom($insertedRoomId);

        if ($room instanceof ERoom) {
            echo "Room loaded successfully: " . json_encode($room) . "\n";
            echo "Test 2: PASSED\n";
        } else {
            echo "Room not found.\n";
            echo "Test 2: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 2: FAILED\n";
    }
}

function testLoadAllRooms(): void
{
    echo "\nTest 3: Loading all rooms\n";
    try {
        $rooms = FRoom::loadAllRoom();

        if (count($rooms) > 0) {
            echo "Rooms loaded successfully. Total: " . count($rooms) . "\n";
            echo "Test 3: PASSED\n";
        } else {
            echo "No rooms found.\n";
            echo "Test 3: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 3: FAILED\n";
    }
}

function testLoadNonExistentRoom(): void
{
    echo "\nTest 4: Loading a non-existent room\n";
    try {
        $room = FRoom::loadRoom(9999); // Non-existent ID

        if ($room === null) {
            echo "Room not found as expected.\n";
            echo "Test 4: PASSED\n";
        } else {
            echo "Room found when it shouldn't be.\n";
            echo "Test 4: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 4: FAILED\n";
    }
}

function testRoomExists(): void
{
    global $insertedRoomId;
    echo "\nTest 4: Checking if room exists\n";
    try {
        $exists = FRoom::existsRoom($insertedRoomId);

        if ($exists) {
            echo "Room exists.\n";
            echo "Test 4: PASSED\n";
        } else {
            echo "Room does not exist.\n";
            echo "Test 4: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 4: FAILED\n";
    }
}

function testUpdateRoom(): void
{
    global $insertedRoomId;
    echo "\nTest 6: Updating room\n";
    if ($insertedRoomId === null) {
        echo "Test 6: ID not available, insertion test must be executed first.\n";
        return;
    }
    try {
        $db = FDatabase::getInstance();
        $roomToUpdate = FRoom::loadRoom($insertedRoomId); // Load the existing room
        $roomToUpdate->setName('Updated Room Name');

        $updateSuccess = FRoom::storeRoom($roomToUpdate); // Assuming store also handles updates

        if ($updateSuccess) {
            echo "Room updated successfully.\n";
            echo "Test 6: PASSED\n";
        } else {
            echo "Room update failed.\n";
            echo "Test 6: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 6: FAILED\n";
    }
}

function testDeleteRoom(): void
{
    global $insertedRoomId;
    echo "\nTest 7: Deleting room\n";
    if ($insertedRoomId === null) {
        echo "Test 7: ID not available, insertion test must be executed first.\n";
        return;
    }
    try {
        $deleteSuccess = FRoom::deleteRoom($insertedRoomId); // Assuming delete is a static method

        if ($deleteSuccess) {
            echo "Room deleted successfully.\n";
            echo "Test 7: PASSED\n";
        } else {
            echo "Room deletion failed.\n";
            echo "Test 7: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 7: FAILED\n";
    }
}

// Execute tests
testInsertLocation(); // Call to test location insertion first
testInsertRoom(); // Now call room insertion test
testLoadRoomById();
testLoadAllRooms();
testLoadNonExistentRoom();
testRoomExists();
testUpdateRoom();
//testDeleteRoom();
