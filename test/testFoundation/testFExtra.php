<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FExtra;
use Entity\EExtra;

// Variabili globali per i test
$insertedId = null;

/**
 * Funzione per creare un oggetto EExtra di test.
 *
 * @return EExtra L'oggetto di test.
 */
function getTestExtraData(): EExtra
{
    return new EExtra(
        null,                // ID (sarà impostato dal database)
        'Test Extra Item',   // Nome
        'Descrizione di test', // Descrizione
        99.99                // Prezzo
    );
}

/**
 * Test: Inserimento di un nuovo extra.
 */
function testInsertExtra(): void
{
    global $insertedId;
    echo "\nTest 1: Inserimento di un nuovo extra\n";
    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $testExtra = getTestExtraData();
        $insertedId = $fExtra->storeExtra($testExtra);

        if ($insertedId !== null) {
            echo "Inserimento riuscito. ID inserito: $insertedId\n";
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
 * Test: Caricamento di un extra tramite ID.
 */
function testLoadExtraById(): void
{
    global $insertedId;
    echo "\nTest 2: Caricamento extra tramite ID\n";
    if ($insertedId === null) {
        echo "ID non disponibile, eseguire prima il test di inserimento.\n";
        return;
    }

    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $extra = $fExtra->loadExtra($insertedId);

        if ($extra instanceof EExtra) {
            echo "Extra caricato correttamente: " . json_encode($extra) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Extra non trovato.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Test: Aggiornamento di un extra.
 */
function testUpdateExtra(): void
{
    global $insertedId;
    echo "\nTest 3: Aggiornamento di un extra\n";
    if ($insertedId === null) {
        echo "ID non disponibile, eseguire prima il test di inserimento.\n";
        return;
    }

    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $updatedData = [
            'description' => 'Updated Test Description',
            'price' => 59.99
        ];

        $updated = $fExtra->updateExtra($insertedId, $updatedData);

        if ($updated) {
            echo "Extra aggiornato correttamente.\n";
            echo "Test 3: PASSATO\n";
        } else {
            echo "Aggiornamento fallito.\n";
            echo "Test 3: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 3: FALLITO\n";
    }
}

/**
 * Test: Verifica esistenza di un extra.
 */
function testExistsExtra(): void
{
    global $insertedId;
    echo "\nTest 4: Verifica esistenza extra\n";
    if ($insertedId === null) {
        echo "ID non disponibile, eseguire prima il test di inserimento.\n";
        return;
    }

    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $exists = $fExtra->existsExtra($insertedId);

        if ($exists) {
            echo "L'extra esiste.\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "L'extra non esiste.\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

/**
 * Test: Cancellazione di un extra.
 */
function testDeleteExtra(): void
{
    global $insertedId;
    echo "\nTest 5: Cancellazione di un extra\n";
    if ($insertedId === null) {
        echo "ID non disponibile, eseguire prima il test di inserimento.\n";
        return;
    }

    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $deleted = $fExtra->deleteExtra($insertedId);

        if ($deleted) {
            echo "Extra cancellato correttamente.\n";
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

/**
 * Test: Caricamento di tutti gli extra.
 */
function testLoadAllExtras(): void
{
    echo "\nTest 6: Caricamento di tutti gli extra\n";
    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $allExtras = $fExtra->loadAllExtra();

        echo "Totale extra caricati: " . count($allExtras) . "\n";
        echo "Dettagli: " . json_encode($allExtras) . "\n";
        echo "Test 6: PASSATO\n";
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 6: FALLITO\n";
    }
}

// Esecuzione dei test
echo "Esecuzione dei test...\n";
testInsertExtra();
testLoadExtraById();
testUpdateExtra();
testExistsExtra();
testLoadAllExtras();
//testDeleteExtra();
