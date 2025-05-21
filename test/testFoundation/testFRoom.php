<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FRoom;
use Entity\ERoom;


/**
 * Funzione per creare un oggetto ERoom di testing
 * 
 * @return ERoom l'oggetto creato
 */
function getTestRoomData(): ERoom {
    return new ERoom (
        null,
        (string) 'areaNameTest 3',
        (int) 20,
        (float) 100
    );
}

/**
 * Inserimento di una nuova stanza
 */
function testCreateRoom(): void {
    echo "\nTest 1: Inserimento di una nuova stanza\n";
    try {
        $fRoom=new FRoom(FDatabase::getInstance());
        $testRoom=getTestRoomData();
        $insertedId=$fRoom->create($testRoom);

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
 * Test: Caricamento di una camera tramite ID.
 */
function testReadRoom(): void {
    $idKnown=1; //ID DA CARICARE
    echo "\nTest 2: Caricamento stanza tramite ID\n";
    try {
        $fRoom = new FRoom(FDatabase::getInstance());
        $room = $fRoom->read($idKnown);

        if ($room instanceof ERoom) {
            echo "Stanza caricata correttamente: " . json_encode($room) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Stanza non trovata.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Test: Aggiornamento di una stanza.
 */
function testUpdateRoom(): void
{
    $existingId=1; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di una stanza\n";
    try {
        $fRoom = new FRoom(FDatabase::getInstance());
        $room = $fRoom->read($existingId);
        if (!$room) {
            echo "ERRORE: extra con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $room->setName('Room Test Name MODIFIED');
        $room->setMaxGuests(100);
        $room->setTax(1);
        $result=$fRoom->update($room);

        if ($result) {
            echo "Stanza aggiornata correttamente.\n";
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
 * Test: Cancellazione di una stanza.
 */
function testDeleteRoom(): void
{
    $idToDelete=1; //ID DA ELIMINARE
    try {
        $fRoom = new FRoom(FDatabase::getInstance());
        $deleted = $fRoom->delete($idToDelete);

        if ($deleted) {
            echo "Stanza cancellata correttamente.\n";
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
 * Test: Verifica esistenza di una stanza.
 */
function testExistsRoom(): void
{
    $existId=2; //ID DA VERIFICARE
    try {
        $fRoom = new FRoom(FDatabase::getInstance());
        $exists = $fRoom->exists($existId);

        if ($exists) {
            echo "La stanza esiste.\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "La stanza non esiste.\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

/**
 * Test: Caricamento di tutte le stanze.
 */
function testLoadAllRooms(): void
{
    echo "\nTest 6: Caricamento di tutte le stanze\n";
    try {
        $fRoom = new FRoom(FDatabase::getInstance());
        $allRooms = $fRoom->readAllRoom();

        echo "Totale stanze caricate: " . count($allRooms) . "\n";
        echo "Dettagli: " . json_encode($allRooms) . "\n";
        echo "Test 6: PASSATO\n";
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 6: FALLITO\n";
    }
}
















// Execute tests
//testCreateRoom();
//testReadRoom();
//testUpdateRoom();
//testDeleteRoom();
//testExistsRoom();
//testLoadAllRooms;


