<?php

// Usare il percorso relativo per includere autoload.php
require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FPayment;
use Foundation\FDatabase;
use Entity\EPayment;


function insertUser(): int
{
    $db = FDatabase::getInstance();

    $userData = [
        'username' => 'testuser',
        'name' => 'John',
        'surname' => 'Doe',
        'birthDate' => '1990-01-01',
        'phone' => '1234567890',
        'image' => null,
        'email' => 'testuser@example.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'ban' => 0,
        'motivation' => null,
    ];

    $idUser = $db->insert('user', $userData);
    if (!$idUser) {
        throw new Exception("Failed to insert user.");
    }

    echo "User inserted successfully. ID: $idUser\n";
    return $idUser;
}

// Inserisce un record nella tabella creditcard e restituisce l'ID generato
function insertCreditCard(int $idUser): int
{
    $db = FDatabase::getInstance();

    $creditCardData = [
        'number' => '1234567890123456',
        'cvv' => '123',
        'expiration' => '2025-12-31',
        'holder' => 'John Doe',
        'type' => 'VISA',
        'idUser' => $idUser,

    ];

    $idCreditCard = $db->insert('creditcard', $creditCardData);
    if (!$idCreditCard) {
        throw new Exception("Failed to insert credit card.");
    }

    echo "Credit card inserted successfully. ID: $idCreditCard\n";
    return $idCreditCard;
}

function insertAddress(): int
{
    $db = FDatabase::getInstance();

    $addressData = [
        'streetAddress' => '123 Main Street',
        'civicNumber' => '10',
        'city' => 'Springfield',
        'postalCode' => '62701',
        'country' => 'USA',
        'state' => 'Illinois',
    ];

    $idAddress = $db->insert('address', $addressData);
    if (!$idAddress) {
        throw new Exception("Failed to insert address.");
    }

    echo "Address inserted successfully. ID: $idAddress\n";
    return $idAddress;
}

function insertOwner(): int
{
    $db = FDatabase::getInstance();

    $ownerData = [
        'username' => 'owneruser',
        'name' => 'Jane',
        'surname' => 'Smith',
        'birthDate' => '1985-05-15',
        'phone' => '9876543210',
        'image' => null,
        'email' => 'owner@example.com',
        'password' => password_hash('securepassword', PASSWORD_DEFAULT),
        'validation' => 1,
    ];

    $idOwner = $db->insert('owner', $ownerData);
    if (!$idOwner) {
        throw new Exception("Failed to insert owner.");
    }

    echo "Owner inserted successfully. ID: $idOwner\n";
    return $idOwner;
}
function insertLocation(int $idAddress, int $idOwner): int
{
    $db = FDatabase::getInstance();

    $locationData = [
        'name' => 'Grand Hall',
        'VATNumber' => '12345678901',
        'description' => 'A premium venue for events.',
        'type' => 'event_space',
        'idOwner' => $idOwner,
        'idAddress' => $idAddress,
        'status' => 'active',
        'views' => 0,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    $idLocation = $db->insert('location', $locationData);
    if (!$idLocation) {
        throw new Exception("Failed to insert location.");
    }

    echo "Location inserted successfully. ID: $idLocation\n";
    return $idLocation;
}


function insertRoom(int $idLocation): int
{
    $db = FDatabase::getInstance();

    $roomData = [
        'name' => 'Conference Room A',
        'capacity' => 50,
        'squareFootage' => 1000,
        'photo' =>  'https://example.com/photo2.jpg', // Puoi aggiungere un URL o un percorso file se necessario
        'price' => 250.00,
        'idLocation' => $idLocation,
    ];

    $idRoom = $db->insert('room', $roomData);
    if (!$idRoom) {
        throw new Exception("Failed to insert room.");
    }

    echo "Room inserted successfully. ID: $idRoom\n";
    return $idRoom;
}


function insertReservation(int $idUser, int $idRoom, int $idEvent): int
{
    $db = FDatabase::getInstance();

    // Verifica che l'utente, la stanza e l'evento esistano
    if (!$db->exists('user', ['idUser' => $idUser])) {
        throw new Exception("User ID $idUser does not exist.");
    }
    if (!$db->exists('room', ['idRoom' => $idRoom])) {
        throw new Exception("Room ID $idRoom does not exist.");
    }
    if (!$db->exists('event', ['idEvent' => $idEvent])) {
        throw new Exception("Event ID $idEvent does not exist.");
    }

    $reservationData = [
        'creationTime' => date('Y-m-d H:i:s'),
        'state' => 'confirmed',
        'durationEvent' => 2,
        'totPrice' => 200.00,
        'idUser' => $idUser,
        'idRoom' => $idRoom,
        'idEvent' => $idEvent,
    ];

    $idReservation = $db->insert('reservation', $reservationData);
    if (!$idReservation) {
        throw new Exception("Failed to insert reservation.");
    }

    echo "Reservation inserted successfully. ID: $idReservation\n";
    return $idReservation;
}


/**
 * @throws Exception
 */
function runTests()
{
    echo "===== Testing FPayment =====\n";

    try {
        // Creazione di una connessione al database
        $db = FDatabase::getInstance();


        // Inserisci un utente
        echo "\nInserimento di un utente\n";
        $idUser = insertUser();

        // Inserisci una carta di credito
        echo "\nInserimento di una carta di credito\n";
        $idCreditCard = insertCreditCard($idUser);

        // Inserisci una stanza
        echo "\nInserimento di una stanza\n";
        $idAddress = insertAddress();
        $idOwner = insertOwner();
        $idLocation = insertLocation($idAddress, $idOwner);
        $idRoom = insertRoom($idLocation);

        // Inserisci una prenotazione
        echo "\nInserimento di una prenotazione\n";
        $idReservation = insertReservation($idUser, $idRoom, 1);

        // Test 1: Inserimento di un pagamento
        echo "\nTest 1: Inserimento di un pagamento\n";

        $payment = new EPayment(null, 150.75, 'pending', new DateTime(), $idCreditCard, $idReservation);
        $result = FPayment::storePayment($payment);

        if ($result) {
            echo "Payment stored successfully. ID assegnato: " . $payment->getIdPayment() . "\n";
            echo "Test 1: PASSATO\n";
        } else {
            echo "Failed to store payment.\n";
            echo "Test 1: FALLITO\n";
        }

        // Test 2: Recupero di un pagamento per ID
        echo "\nTest 2: Recupero di un pagamento per ID\n";
        $retrievedPayment = FPayment::getPaymentById($payment->getIdPayment());

        if ($retrievedPayment) {
            echo "Payment retrieved successfully:\n";
            print_r($retrievedPayment);
            echo "Test 2: PASSATO\n";
        } else {
            echo "Failed to retrieve payment.\n";
            echo "Test 2: FALLITO\n";
        }

        // Test 3: Aggiornamento di un pagamento
        echo "\nTest 3: Aggiornamento di un pagamento\n";
        $retrievedPayment->setState('completed');
        $updateResult = FPayment::updatePayment($retrievedPayment);

        if ($updateResult) {
            echo "Payment updated successfully.\n";
            echo "Test 3: PASSATO\n";
        } else {
            echo "Failed to update payment.\n";
            echo "Test 3: FALLITO\n";
        }

        // Test 4: Recupero di tutti i pagamenti con uno stato specifico
        echo "\nTest 4: Recupero di tutti i pagamenti con stato 'completed'\n";
        $completedPayments = FPayment::loadAllPaymentBy(['state' => 'completed']);

        if (!empty($completedPayments)) {
            echo "Payments retrieved successfully:\n";
            print_r($completedPayments);
            echo "Test 4: PASSATO\n";
        } else {
            echo "No payments found.\n";
            echo "Test 4: FALLITO\n";
        }

        // Test 5: Verifica dell'esistenza di un pagamento
        echo "\nTest 5: Verifica dell'esistenza di un pagamento\n";
        $exists = FPayment::existsPayment(['idPayment' => $retrievedPayment->getIdPayment()]);
        if ($exists) {
            echo "Payment exists.\n";
            echo "Test 5: PASSATO\n";
        } else {
            echo "Payment does not exist.\n";
            echo "Test 5: FALLITO\n";
        }

        // Test 6: Validazione dello stato di un pagamento
        echo "\nTest 6: Validazione dello stato di un pagamento\n";
        $isValid = FPayment::validatePayment($retrievedPayment);
        if ($isValid) {
            echo "Payment state is valid.\n";
            echo "Test 6: PASSATO\n";
        } else {
            echo "Payment state is invalid.\n";
            echo "Test 6: FALLITO\n";
        }

        // Test 7: Calcolo dell'importo totale dei pagamenti
        echo "\nTest 7: Calcolo dell'importo totale dei pagamenti\n";
        $totalAmount = FPayment::getTotalPaymentsAmount(['state' => 'completed']);
        if ($totalAmount > 0) {
            echo "Total payments amount: $totalAmount\n";
            echo "Test 7: PASSATO\n";
        } else {
            echo "Failed to calculate total payments amount.\n";
            echo "Test 7: FALLITO\n";
        }

        // Test 8: Recupero di tutti i pagamenti
        echo "\nTest 8: Recupero di tutti i pagamenti\n";
        $allPayments = FPayment::loadAllPayments();
        if (!empty($allPayments)) {
            echo "All payments retrieved successfully:\n";
            print_r($allPayments);
            echo "Test 8: PASSATO\n";
        } else {
            echo "Failed to retrieve all payments.\n";
            echo "Test 8: FALLITO\n";
        }


        /*
        // Test 11: Eliminazione di un pagamento
        echo "\nTest 11: Eliminazione di un pagamento\n";
        $deleteResult = FPayment::deletePayment($retrievedPayment->getIdPayment());

        if ($deleteResult) {
            echo "Payment deleted successfully.\n";
            echo "Test 11: PASSATO\n";
        } else {
            echo "Failed to delete payment.\n";
            echo "Test 11: FALLITO\n";
        }
        */

    } catch (Exception $e) {
        echo "An error occurred during the tests: " . $e->getMessage() . "\n";
    }

    echo "\n===== Testing Completed =====\n";
}

// Esegui i test
try {
    runTests();
} catch (Exception $e) {
    echo "Errore durante i test: " . $e->getMessage() . "\n";
}
