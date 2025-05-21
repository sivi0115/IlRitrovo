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
        (string)"Extra Test Name 4",   // Nome
        (float)20                // Prezzo
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
        $insertedId = $fExtra->create($testExtra);

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
    $idKnown=3; //ID DA CARICARE
    echo "\nTest 2: Caricamento extra tramite ID\n";
    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $extra = $fExtra->read($idKnown);

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
    $existingId=1; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di un extra\n";
    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $extra = $fExtra->read($existingId);
        if (!$extra) {
            echo "ERRORE: extra con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $extra->setNameExtra('Extra Test Name MODIFIED');
        $extra->setPriceExtra(1);
        $result=$fExtra->update($extra);

        if ($result) {
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
    $existId=2; //ID DA VERIFICARE
    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $exists = $fExtra->exists($existId);

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
    $idToDelete=1; //ID DA ELIMINARE
    try {
        $fExtra = new FExtra(FDatabase::getInstance());
        $deleted = $fExtra->delete($idToDelete);

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
        $allExtras = $fExtra->readAllExtra();

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
//testInsertExtra(); FATTO
//testLoadExtraById(1); FATTO
//testUpdateExtra(); FATTO
//testExistsExtra(); 
//testLoadAllExtras();
//testDeleteExtra(); FATTO
