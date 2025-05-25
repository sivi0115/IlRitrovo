<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FTable;
use Entity\ETable;


/**
 * Funzione per creare un oggetto ETable di testing
 * 
 * @return ETable l'oggetto creato
 */
function getTestTableData(): ETable {
    return new ETable (
        1,
        (string) 'Tavolo1',
        (int) 4,
    );
}

/**
 * Inserimento di una nuovo tavolo 
 */
function testCreateTable(): void {
    echo "\nTest 1: Inserimento di una nuovo tavolo\n";
    try {
        $fTable=new FTable(FDatabase::getInstance());
        $testTable =getTestTableData();
        $insertedId=$fTable->create($testTable);

        if($insertedId !== null) {
            echo "Inserimento riuscito. ID inserito: $insertedId\n";
            echo "Test 1: PASSATO\n";
        } else {
            echo "Inserimento fallito. \n";
            echo "Test 1: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 1: FALLITO\n";
    }

}

/**
 * Test: Caricamento di un nuovo tavolo tramite ID.
 */
function testReadTable(): void {
    $idKnown=3; //ID DA CARICARE
    echo "\nTest 2: Caricamento tavolo tramite ID\n";
    try {
        $fTable = new FTable(FDatabase::getInstance());
        $table = $fTable->read($idKnown);

        if ($table instanceof ETable) {
            echo "Tavolo caricato correttamente: " . json_encode($table) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Tavolo non trovato.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Test: Aggiornamento di un tavolo.
 */
function testUpdateTable(): void
{
    $existingId=3; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di un tavolo\n";
    try {
        $fTable = new FTable(FDatabase::getInstance());
        $table = $fTable->read($existingId);
        if (!$table) {
            echo "ERRORE: extra con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $table->setName('Table Test Name MODIFIED');
        $table->setMaxGuests(50);
        $result=$fTable->update($table);

        if ($result) {
            echo "Tavolo aggiornato correttamente.\n";
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
 * Test: Cancellazione di un tavolo.
 */
function testDeleteTable(): void
{
    $idToDelete=3; //ID DA ELIMINARE
    try {
        $fTable = new FTable(FDatabase::getInstance());
        $deleted = $fTable->delete($idToDelete);

        if ($deleted) {
            echo "Tavolo cancellato correttamente.\n";
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
 * Test: Verifica esistenza di un tavolo.
 */
function testExistsTable(): void
{
    $existId=4; //ID DA VERIFICARE
    try {
        $fTable = new FTable(FDatabase::getInstance());
        $exists = $fTable->exists($existId);

        if ($exists) {
            echo "Il tavolo esiste.\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "Il tavolo non esiste.\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

/**
 * Test: Caricamento di tutti i tavoli.
 */
function testLoadAllTables(): void
{
    echo "\nTest 6: Caricamento di tutti i tavoli\n";
    try {
        $fTable = new FTable(FDatabase::getInstance());
        $allTables = $fTable->readAllTables();

        echo "Totale tavoli caricati: " . count($allTables) . "\n";
        echo "Dettagli: " . json_encode($allTables) . "\n";
        echo "Test 6: PASSATO\n";
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 6: FALLITO\n";
    }
}
















// Execute tests
testCreateTable();
//testReadTable();
//testUpdateTable();
//testDeleteTable();
//testExistsTable();
//testLoadAllTables();