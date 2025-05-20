<?php

// Usare il percorso relativo per includere autoload.php
require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FAddress;
use Entity\EAddress;

/**
 * @throws Exception
 */
function runTests()
{
    // Creare un'istanza di FDatabase e FAddress
    $db = FDatabase::getInstance();
    $fAddress = new FAddress($db);

    echo "===== Testing FAddress =====\n";

    // Test 1: Inserimento di un nuovo indirizzo
    echo "\nTest 1: Inserimento di un nuovo indirizzo\n";
    $address = new EAddress("Via Test", 1, "Milano", "20100", "Italia", "Lombardia");
    $insertedId = $fAddress->insertAddress($address);

    if ($insertedId) {
        echo "Inserimento riuscito! ID: $insertedId\n";
        echo "Test 1: PASSATO\n";
    } else {
        echo "Errore durante l'inserimento. Forse l'indirizzo esiste già.\n";
        echo "Test 1: FALLITO\n";
    }

    // Test 2: Caricamento di un indirizzo
    if ($insertedId) {
        echo "\nTest 2: Caricamento di un indirizzo\n";
        $loadedAddress = $fAddress->loadAddress($insertedId);

        if ($loadedAddress) {
            echo "Indirizzo caricato con successo: \n";
            echo "Street: " . $loadedAddress->getStreetAddress() . "\n";
            echo "City: " . $loadedAddress->getCity() . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Errore durante il caricamento dell'indirizzo con ID $insertedId\n";
            echo "Test 2: FALLITO\n";
        }
    }

    // Test 3: Controllo dell'esistenza di un indirizzo
    echo "\nTest 3: Controllo dell'esistenza di un indirizzo\n";
    $exists = $fAddress->addressExists($address);

    if ($exists) {
        echo "L'indirizzo esiste nel database.\n";
        echo "Test 3: PASSATO\n";
    } else {
        echo "L'indirizzo non esiste nel database.\n";
        echo "Test 3: FALLITO\n";
    }

    // Test 4: Aggiornamento di un indirizzo
    if ($insertedId) {
        echo "\nTest 4: Aggiornamento di un indirizzo\n";
        $newData = [
            'city' => 'Roma',
            'state' => 'Lazio'
        ];
        $updateSuccess = $fAddress->updateAddress($insertedId, $newData);

        if ($updateSuccess) {
            echo "Aggiornamento riuscito!\n";
            $updatedAddress = $fAddress->loadAddress($insertedId);
            echo "Nuova città: " . $updatedAddress->getCity() . "\n";
            echo "Nuovo stato: " . $updatedAddress->getState() . "\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "Errore durante l'aggiornamento dell'indirizzo.\n";
            echo "Test 4: FALLITO\n";
        }
    }

    // Test 5: Caricamento di indirizzi per condizione
    echo "\nTest 5: Caricamento di indirizzi per condizione\n";
    $conditions = ['city' => 'Roma'];
    $addresses = $fAddress->loadAddressesByCondition($conditions);

    if (!empty($addresses)) {
        echo "Indirizzi trovati:\n";
        foreach ($addresses as $addr) {
            echo "- " . $addr->getStreetAddress() . ", " . $addr->getCity() . "\n";
        }
        echo "Test 5: PASSATO\n";
    } else {
        echo "Nessun indirizzo trovato con la condizione specificata.\n";
        echo "Test 5: FALLITO\n";
    }

    /**
    // Test 6: Eliminazione di un indirizzo
    if ($insertedId) {
        echo "\nTest 6: Eliminazione di un indirizzo\n";
        $deleteSuccess = $fAddress->deleteAddress($insertedId);

        if ($deleteSuccess) {
            echo "Indirizzo eliminato con successo.\n";
            $existsAfterDelete = $fAddress->addressExists($address);
            if (!$existsAfterDelete) {
                echo "Conferma: l'indirizzo non esiste più nel database.\n";
                echo "Test 6: PASSATO\n";
            } else {
                echo "Conferma: l'indirizzo esiste ancora nel database.\n";
                echo "Test 6: FALLITO\n";
            }
        } else {
            echo "Errore durante l'eliminazione dell'indirizzo.\n";
            echo "Test 6: FALLITO\n";
        }
    }
     * */

    echo "\n===== Testing Completed =====\n";
}

// Esegui i test
try {
    runTests();
} catch (Exception $e) {
    echo "Errore durante i test: " . $e->getMessage() . "\n";
}
