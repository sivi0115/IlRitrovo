<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FReview;
use Entity\EReview;
use Entity\EOwner;
use Entity\EAddress;

/**
 * Function to get test data for an owner.
 * @throws Exception
 */
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
 * Function to insert an owner into the database and return the ID.
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

/**
 * Function to get test data for an address.
 * @throws Exception
 */
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
 * Function to insert an address into the database and return the ID.
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
 * Funzione per ottenere i dati di test di un user.
 * @throws Exception
 */
function getTestUserData(): array
{
    return [
        'username' => 'testuser_',
        'name' => 'Test',
        'surname' => 'User',
        'birthDate' => new DateTime('2000-01-01'),
        'phone' => '1234567890',
        'image' => 'https://example.com/image.jpg',
        'email' => 'testuser@example.com',
        'password' => password_hash('securePassword@123', PASSWORD_DEFAULT),
        'ban' => 0, // Not banned
        'motivation' => null // No ban motivation
    ];
}


/**
 * Funzione per inserire un utente di test nel database e restituire l'ID.
 * @throws Exception
 */
function insertTestUser(): int
{
    $db = FDatabase::getInstance();
    $testUser = getTestUserData();

    $data = [
        'username' => $testUser['username'],
        'name' => $testUser['name'],
        'surname' => $testUser['surname'],
        'birthDate' => $testUser['birthDate']->format('Y-m-d'),
        'phone' => $testUser['phone'],
        'image' => $testUser['image'],
        'email' => $testUser['email'],
        'password' => $testUser['password'],
        'ban' => $testUser['ban'],
        'motivation' => $testUser['motivation']
    ];

    $userId = $db->insert('user', $data);
    if ($userId === null) {
        throw new Exception('Errore durante l\'inserimento dell\'utente.');
    }

    // Debug: Controlla se l'utente esiste nel database
    $insertedUser = $db->load('user', 'idUser', $userId);
    if ($insertedUser === null) {
        throw new Exception("Errore: L'utente non è stato trovato nel database dopo l'inserimento.");
    }

    echo "Utente di test inserito correttamente con ID: $userId\n";
    return $userId;
}

/**
 * Funzione per ottenere i dati di test di una location.
 * @throws Exception
 */
function getTestLocationData(): array
{
    return [
        'name' => 'Test Location',
        'VATNumber' => '12345678901',
        'description' => 'A test location for reviews.',
        'photo' => 'https://example.com/location.jpg',
        'type' => 'Restaurant',
        'idOwner' => insertTestOwner(), // Insert test owner and use their ID
        'idAddress' => insertTestAddress() // Insert a test address and use its ID
    ];
}

/**
 * Funzione per inserire una location di test nel database e restituire l'ID.
 * @throws Exception
 */
function insertTestLocation(): int
{
    $db = FDatabase::getInstance();
    $testLocation = getTestLocationData();

    $data = [
        'name' => $testLocation['name'],
        'VATNumber' => $testLocation['VATNumber'],
        'description' => $testLocation['description'],
        'type' => $testLocation['type'],
        'idOwner' => $testLocation['idOwner'],
        'idAddress' => $testLocation['idAddress']
    ];

    $locationId = $db->insert('location', $data); // Assicurati di usare il metodo corretto
    if ($locationId === null) {
        throw new Exception('Errore durante l\'inserimento della location.');
    }

    return $locationId;
}

/**
 * Funzione per ottenere i dati di test di una review.
 * @throws Exception
 */
function getTestReviewData(): EReview
{
    $userId = insertTestUser(); // Inserisce un utente di test e ottiene l'ID
    $locationId = insertTestLocation(); // Inserisce una location di test e ottiene l'ID
    return new EReview(
        null, // idReview (sarà auto-generato)
        'This is a test review.',
        new DateTime(),
        false,
        5, // 5 stelle
        'ciao',
        $userId, // idUser (dall'utente di test appena creato)
        $locationId  // idLocation (dalla location di test appena creata)
    );
}

$insertedId = null; // Variabile per memorizzare l'ID della review inserita

/**
 * Funzione per testare l'inserimento di una nuova review.
 */
function testInsertReview(): void
{
    global $insertedId;
    echo "\nTest 1: Inserimento di una nuova review\n";
    try {
        $userId = insertTestUser(); // Inserisce un utente di test
        $locationId = insertTestLocation(); // Inserisce una location di test

        if (!$userId || !$locationId) {
            throw new Exception('Inserimento di test user o location fallito.');
        }

        echo "ID utente per la review: $userId\n";
        echo "ID location per la review: $locationId\n";

        $testReview = FReview::createEntityReview(
            "Test review body",
            new DateTime(),
            false,
            5,
            "", // Risposta vuota
            $userId, // ID utente di test
            $locationId // ID location di test
        );

        $insertId = FReview::storeReview($testReview);

        if ($insertId !== null) {
            $insertedId = $insertId; // Salva l'ID inserito per i test successivi
            echo "Inserimento riuscito. ID review inserito: $insertedId\n";
            echo "Test 1: PASSATO\n";
        } else {
            echo "Inserimento fallito.\n";
            echo "Test 1: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 1: FALLITO\n";
    }
}


/**
 * Funzione per testare il caricamento di una review tramite ID.
 */
function testLoadReviewById(): void
{
    global $insertedId;
    echo "\nTest 2: Caricamento review tramite ID\n";
    if ($insertedId === null) {
        echo "Test 2: ID non disponibile, il test di inserimento deve essere eseguito prima.\n";
        return;
    }
    try {
        $review = FReview::loadReview($insertedId);

        if ($review instanceof EReview) {
            echo "Review caricata correttamente: " . json_encode($review) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Review non trovata.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Funzione per testare il caricamento di una review inesistente.
 */
function testLoadNonExistentReview(): void
{
    echo "\nTest 3: Caricamento di una review inesistente\n";
    try {
        $review = FReview::loadReview(9999); // ID inesistente

        if ($review === null) {
            echo "Review non trovata come previsto.\n";
            echo "Test 3: PASSATO\n";
        } else {
            echo "Review trovata quando non dovrebbe esserci.\n";
            echo "Test 3: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 3: FALLITO\n";
    }
}

/**
 * Funzione per testare l'aggiornamento di una review.
 */
function testUpdateReview(): void
{
    global $insertedId;
    echo "\nTest 4: Aggiornamento di una review\n";
    if ($insertedId === null) {
        echo "Test 4: ID non disponibile, il test di inserimento deve essere eseguito prima.\n";
        return;
    }
    try {
        $review = FReview::loadReview($insertedId);
        if ($review instanceof EReview) {
            $review->setBody("Updated review body");
            FReview::updateReview($review);
            echo "Review aggiornata correttamente.\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "Review non trovata per aggiornamento.\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

/**
 * Funzione per testare la cancellazione di una review.
 */
function testDeleteReview(): void
{
    global $insertedId;
    echo "\nTest 5: Cancellazione review\n";
    if ($insertedId === null) {
        echo "Test 5: ID non disponibile, il test di inserimento deve essere eseguito prima.\n";
        return;
    }
    try {
        $deleted = FReview::deleteReview($insertedId);

        if ($deleted) {
            echo "Review cancellata correttamente.\n";
            echo "Test 5: PASSATO\n";
        } else {
            echo "Cancellazione fallita.\n";
            echo "Test 5: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 5: FALLITO\n";
    }
}

// Esecuzione dei test
echo "Esecuzione dei test...\n";

testInsertReview();
testLoadReviewById();
testLoadNonExistentReview();
testUpdateReview();
//testDeleteReview();

