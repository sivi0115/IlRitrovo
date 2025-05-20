<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FOwner;
use Entity\EOwner;

/**
 * Funzione per ottenere i dati di un test owner.
 */
function getTestOwnerData(): EOwner
{
    return new EOwner(
        0, // idOwner inizialmente 0, sarà gestito dal database
        'testUsername',
        'Test',
        'Owner',
        new DateTime('1990-01-01'),
        '1234567890',
        'https://example.com/image.jpg',
        'test@example.com',
        'securePassword@123',
        true // validation
    );
}

/**
 * Funzione per testare l'inserimento di un nuovo owner.
 */
function testInsertOwner(): int
{
    echo "\nTest 1: Inserimento di un nuovo owner\n";
    try {
        $fOwner = new FOwner();
        $testOwner = getTestOwnerData();
        $insertedId = $fOwner->saveOwner($testOwner);

        if ($insertedId !== 0) {
            echo "Inserimento riuscito. ID inserito: $insertedId\n";
            echo "Test 1: PASSATO\n";
            return $insertedId;
        } else {
            echo "Inserimento fallito.\n";
            echo "Test 1: FALLITO\n";
            return 0;
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 1: FALLITO\n";
        return 0;
    }
}

/**
 * Funzione per testare il caricamento di un owner tramite ID.
 */
function testLoadOwnerById(int $id): void
{
    echo "\nTest 2: Caricamento owner tramite ID\n";
    try {
        $fOwner = new FOwner();
        $owner = $fOwner->loadOwner($id);

        if ($owner instanceof EOwner) {
            echo "Owner caricato correttamente: " . json_encode($owner) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Owner non trovato.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Funzione per testare il caricamento di un owner inesistente.
 */
function testLoadNonExistentOwner(): void
{
    echo "\nTest 3: Caricamento di un owner inesistente\n";
    try {
        $fOwner = new FOwner();
        $owner = $fOwner->loadOwner(9999); // ID inesistente

        if ($owner === null) {
            echo "Owner non trovato come previsto.\n";
            echo "Test 3: PASSATO\n";
        } else {
            echo "Owner trovato quando non dovrebbe esserci.\n";
            echo "Test 3: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 3: FALLITO\n";
    }
}

/**
 * Funzione per testare l'esistenza di un owner nel database.
 */
function testOwnerExists(EOwner $testOwner): void
{
    echo "\nTest 4: Verifica esistenza owner\n";
    try {
        $fOwner = new FOwner();
        $exists = $fOwner->ownerExists($testOwner);

        if ($exists) {
            echo "Owner esiste già.\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "Owner non esistente.\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

/**
 * Funzione per testare l'aggiornamento di un owner.
 */
function testUpdateOwner(int $id): void
{
    echo "\nTest 5: Aggiornamento owner\n";
    try {
        $fOwner = new FOwner();
        $owner = $fOwner->loadOwner($id);

        if ($owner instanceof EOwner) {
            $owner->setPhone('9876543210'); // Cambia il numero di telefono
            $updated = $fOwner->updateOwner($owner, $id);

            if ($updated) {
                echo "Aggiornamento riuscito.\n";
                echo "Test 5: PASSATO\n";
            } else {
                echo "Aggiornamento fallito.\n";
                echo "Test 5: FALLITO\n";
            }
        } else {
            echo "Owner non trovato per l'aggiornamento.\n";
            echo "Test 5: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 5: FALLITO\n";
    }
}

/**
 * Funzione per testare la cancellazione di un owner.
 */
function testDeleteOwner(int $id): void
{
    echo "\nTest 6: Cancellazione owner\n";
    try {
        $fOwner = new FOwner();
        $deleted = $fOwner->deleteOwner($id);

        if ($deleted) {
            echo "Owner cancellato correttamente.\n";
            echo "Test 6: PASSATO\n";
        } else {
            echo "Cancellazione fallita.\n";
            echo "Test 6: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 6: FALLITO\n";
    }



}

/**
 * Funzione per testare il caricamento di una recensione tramite ID.
 */
function testGetReviewById(): void
{
    echo "\nTest 7: Caricamento recensione tramite ID\n";
    try {
        $fOwner = new FOwner();
        $review = $fOwner->getReviewById(1); // Usa un ID di test valido dalla tabella review

        if ($review !== null) {
            echo "Recensione caricata correttamente: " . json_encode($review) . "\n";
            echo "Test 7: PASSATO\n";
        } else {
            echo "Recensione non trovata.\n";
            echo "Test 7: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 7: FALLITO\n";
    }
}

/**
 * Funzione per testare l'aggiunta di una risposta a una recensione.
 */
function testReplyToReview(): void
{
    echo "\nTest 8: Risposta a una recensione\n";
    try {
        $fOwner = new FOwner();
        $replied = $fOwner->replyToReview(1, "Grazie per il feedback!"); // ID e risposta di test

        if ($replied) {
            echo "Risposta aggiunta correttamente alla recensione.\n";
            echo "Test 8: PASSATO\n";
        } else {
            echo "Aggiunta della risposta fallita.\n";
            echo "Test 8: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 8: FALLITO\n";
    }

}

/**
 * Esecuzione dei test
 */
echo "Esecuzione dei test...\n";

// Test inserimento
$insertedId = testInsertOwner();

if ($insertedId > 0) {
    // Test caricamento
    testLoadOwnerById($insertedId);

    // Test caricamento owner inesistente
    testLoadNonExistentOwner();

    // Test esistenza
    $testOwner = getTestOwnerData();
    $testOwner->setIdOwner($insertedId);
    testOwnerExists($testOwner);

    // Test aggiornamento
    testUpdateOwner($insertedId);

    // Test caricamento recensione
    testGetReviewById();

    // Test risposta a recensione
    testReplyToReview();

    // Test cancellazione
    //testDeleteOwner($insertedId);
}
