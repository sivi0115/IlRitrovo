<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Entity\EAdmin;
use Entity\EUser;
use Foundation\FDatabase;
use Foundation\FAssistanceTicket;
use Entity\EAssistanceTicket;

/**
 * Function to get test admin data.
 */
function getTestAdminData(): EAdmin
{
    return new EAdmin(
        null,
        'test_username',
        'Test',
        'Admin',
        new DateTime('1980-01-01'),
        '1234567890',
        'https://example.com/image.jpg',
        'admin@example.com',
        'securePassword@123',
        'Manager'
    );
}

/**
 * Inserts a test admin into the database and returns the generated ID.
 * @throws Exception
 */
function insertTestAdmin(): int
{
    $db = FDatabase::getInstance();
    $testAdmin = getTestAdminData();

    $data = [
        'username' => $testAdmin->getUsername(),
        'name' => $testAdmin->getName(),
        'surname' => $testAdmin->getSurname(),
        'birthDate' => $testAdmin->getBirthDate()->format('Y-m-d'),
        'phone' => $testAdmin->getPhone(),
        'image' => $testAdmin->getImage(),
        'email' => $testAdmin->getEmail(),
        'password' => password_hash($testAdmin->getPassword(), PASSWORD_DEFAULT),
        'mansion' => $testAdmin->getMansion(),
    ];

    $adminId = $db->insert('admin', $data);
    if ($adminId === null) {
        throw new Exception('Error inserting admin.');
    }

    return $adminId;
}

/**
 * Function to get test user data.
 */
function getTestUserData(): EUser
{
    return new EUser(
        null,
        'test_user',
        'Test',
        'User',
        new DateTime('1990-01-01'),
        '9876543210',
        'https://example.com/user_image.jpg',
        'testuser@example.com',
        'secureUserPassword@123',
        0,
        null
    );
}

/**
 * Inserts a test user into the database and returns the generated ID.
 * @throws Exception
 */
function insertTestUser(): int
{
    $db = FDatabase::getInstance();
    $testUser = getTestUserData();

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
        throw new Exception('Error inserting user.');
    }

    return $userId;
}

/**
 * Function to get test assistance ticket data.
 * @throws Exception
 */
function getTestAssistanceTicketData(): EAssistanceTicket
{
    $adminId = insertTestAdmin();
    $userId = insertTestUser();

    return new EAssistanceTicket(
        null,
        'Test message',
        'Test object',
        new DateTime(),
        null,
        $adminId,
        $userId
    );
}

$insertedId = null;

/**
 * Test inserting a new assistance ticket.
 */
function testInsertTicket(): void
{
    global $insertedId;
    echo "\nTest 1: Insert a new assistance ticket\n";
    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $testTicket = getTestAssistanceTicketData();
        $fTicket->saveTicket($testTicket);
        $insertedId = $testTicket->getIdTicket();

        if ($insertedId !== null) {
            echo "Insert successful. Inserted ID: $insertedId\n";
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

/**
 * Test loading an assistance ticket by ID.
 */
function testLoadTicketById(): void
{
    global $insertedId;
    echo "\nTest 2: Load assistance ticket by ID\n";
    if ($insertedId === null) {
        echo "Test 2: ID not available, insert test must run first.\n";
        return;
    }
    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $ticket = $fTicket->loadTicket($insertedId);

        if ($ticket instanceof EAssistanceTicket) {
            echo "Assistance ticket loaded successfully: " . json_encode($ticket) . "\n";
            echo "Test 2: PASSED\n";
        } else {
            echo "Assistance ticket not found.\n";
            echo "Test 2: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 2: FAILED\n";
    }
}

/**
 * Test loading a non-existent assistance ticket.
 */
function testLoadNonExistentTicket(): void
{
    echo "\nTest 3: Load non-existent assistance ticket\n";
    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $ticket = $fTicket->loadTicket(9999);

        if ($ticket === null) {
            echo "Non-existent assistance ticket not found as expected.\n";
            echo "Test 3: PASSED\n";
        } else {
            echo "Unexpectedly found non-existent assistance ticket.\n";
            echo "Test 3: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 3: FAILED\n";
    }
}

/**
 * Test checking existence of an assistance ticket.
 */
function testTicketExists(): void
{
    global $insertedId;
    echo "\nTest 4: Check existence of assistance ticket\n";
    if ($insertedId === null) {
        echo "Test 4: ID not available, insert test must run first.\n";
        return;
    }
    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $exists = $fTicket->existsTicket($insertedId);

        if ($exists) {
            echo "Assistance ticket exists.\n";
            echo "Test 4: PASSED\n";
        } else {
            echo "Assistance ticket does not exist.\n";
            echo "Test 4: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 4: FAILED\n";
    }
}

/**
 * Test deleting an assistance ticket.
 */
function testDeleteTicket(): void
{
    global $insertedId;
    echo "\nTest 5: Delete assistance ticket\n";
    if ($insertedId === null) {
        echo "Test 5: ID not available, insert test must run first.\n";
        return;
    }
    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $deleted = $fTicket->deleteTicket($insertedId);

        if ($deleted) {
            echo "Assistance ticket deleted successfully.\n";
            echo "Test 5: PASSED\n";
        } else {
            echo "Failed to delete assistance ticket.\n";
            echo "Test 5: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 5: FAILED\n";
    }
}

/**
 * Test loading an assistance ticket by ID and creation date.
 */
function testLoadByDate(): void
{
    global $insertedId;
    echo "\nTest 6: Load assistance ticket by ID and creation date\n";

    if ($insertedId === null) {
        echo "Test 6: ID not available, insert test must run first.\n";
        return;
    }

    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        // Retrieve the ticket to get the exact creation time
        $ticket = $fTicket->loadTicket($insertedId);
        if (!$ticket) {
            echo "Test 6: Failed to load ticket to fetch creationTime.\n";
            return;
        }

        $creationTime = $ticket->getCreationTime();
        echo "Testing with creationTime: " . $creationTime->format('Y-m-d H:i:s') . "\n";

        // Fetch the ticket using the `loadByDate` method
        $loadedTicket = $fTicket->loadByDate($insertedId, $creationTime);

        if ($loadedTicket instanceof EAssistanceTicket) {
            echo "Assistance ticket loaded successfully by date: " . json_encode($loadedTicket) . "\n";
            echo "Test 6: PASSED\n";
        } else {
            echo "Assistance ticket not found by date.\n";
            echo "Test 6: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 6: FAILED\n";
    }
}

function testUpdateTicket(): void
{
    global $insertedId;
    echo "\nTest 7: Update assistance ticket\n";
    if ($insertedId === null) {
        echo "Test 7: ID not available, insert test must run first.\n";
        return;
    }

    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $updatedFields = ['message' => 'Updated message', 'reply' => 'Updated reply'];
        $updated = $fTicket->updateTicket($insertedId, $updatedFields);

        if ($updated) {
            echo "Assistance ticket updated successfully.\n";
            echo "Test 7: PASSED\n";
        } else {
            echo "Failed to update assistance ticket.\n";
            echo "Test 7: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 7: FAILED\n";
    }
}

function testLoadByInvalidDate(): void
{
    echo "\nTest 8: Load assistance ticket by invalid date\n";
    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $invalidDate = new DateTime('2000-01-01 00:00:00');
        $ticket = $fTicket->loadByDate(9999, $invalidDate);

        if ($ticket === null) {
            echo "No assistance ticket found for invalid date as expected.\n";
            echo "Test 8: PASSED\n";
        } else {
            echo "Unexpectedly found assistance ticket for invalid date.\n";
            echo "Test 8: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 8: FAILED\n";
    }
}

function testUpdateReply(): void
{
    global $insertedId;
    echo "\nTest 9: Update assistance ticket reply\n";
    if ($insertedId === null) {
        echo "Test 9: ID not available, insert test must run first.\n";
        return;
    }

    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $updated = $fTicket->updateReply($insertedId, 'This is the updated reply.');

        if ($updated) {
            echo "Reply updated successfully.\n";
            echo "Test 9: PASSED\n";
        } else {
            echo "Failed to update reply.\n";
            echo "Test 9: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 9: FAILED\n";
    }
}

function testGetLastIdTicket(): void
{
    echo "\nTest 10: Get last inserted ticket ID\n";
    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $lastId = $fTicket->getLastIdTicket();

        if ($lastId !== null) {
            echo "Last inserted ticket ID: $lastId\n";
            echo "Test 10: PASSED\n";
        } else {
            echo "No tickets found in the database.\n";
            echo "Test 10: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 10: FAILED\n";
    }
}

/**
 * Test loading all assistance tickets by user ID.
 */
function testGetTicketsByUser(): void
{
    global $insertedId;
    echo "\nTest 11: Get assistance tickets by user ID\n";
    if ($insertedId === null) {
        echo "Test 11: ID not available, insert test must run first.\n";
        return;
    }

    try {
        $db = FDatabase::getInstance();
        $fTicket = new FAssistanceTicket($db);

        $tickets = $fTicket->getTicketsByUser(1); // Assuming user ID is 1

        if (count($tickets) > 0) {
            echo "Tickets retrieved successfully: " . json_encode($tickets) . "\n";
            echo "Test 11: PASSED\n";
        } else {
            echo "No tickets found for the user.\n";
            echo "Test 11: FAILED\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Test 11: FAILED\n";
    }
}




// Run the tests
echo "Running tests...\n";

testInsertTicket();
testLoadTicketById();
testLoadNonExistentTicket();
testTicketExists();
testLoadByDate();
testUpdateTicket();
testLoadByInvalidDate();
testUpdateReply();
testGetLastIdTicket();
testGetTicketsByUser();
//testDeleteTicket();
