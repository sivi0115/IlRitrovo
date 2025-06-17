<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FReservation;
use Entity\EReservation;
use Entity\EUser;
use Entity\ERoom;
use Entity\ETable;
use Entity\TimeFrame;
use Entity\EExtra;
use Foundation\FExtra;

/**
 * Funzione per creare un oggetto EReservation di test.
 *
 * @return EReservation L'oggetto di test.
 */
function getTestReservationData(): EReservation
{
    $extraFormDb=new EExtra(1, 'Palloncini', 10.00);
    return new EReservation(
        1,
        1,
        null,
        1,
        new DateTime('2025-12-13'),
        new DateTime('2025-12-25'),
        TimeFrame::PRANZO,
        'confirmed',
        4,
        'allergiaTest',
        [$extraFormDb] 

    );
}

/**
 * Funzione per creare un oggetto nel db
 */
function testCreateReservation(): void {
    global $insertedId;
    echo "\nTest 1: Inserimento di una nuova Prenotazione\n";
    try {
        $fReservation = new FReservation(FDatabase::getInstance());
        $testReservation = getTestReservationData();
        $insertedId = $fReservation->create($testReservation);

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
 * Funzione per caricare una prenotazione dal db 
 */
function testReadReservation(): void {
    $idKnown=1; //ID DA CARICARE
    echo "\nTest 2: Caricamento Prenotazione tramite il suo ID\n";
    try {
        $fReservation = new FReservation(FDatabase::getInstance());
        $reservation = $fReservation->read($idKnown);

        if ($reservation instanceof EReservation) {
            echo "Prenotazione caricata correttamente: " . json_encode($reservation) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Prenotazione non trovata.\n";
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
function testUpdateReservation(): void
{
    $existingId=1; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di una prenotazione\n";
    try {
        $fReservation = new FReservation(FDatabase::getInstance());
        $reservation = $fReservation->read($existingId);
        
        if (!$reservation) {
            echo "ERRORE: Prenotazione con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $reservation->setReservationDate(new DateTime('2025-12-26'));
        $reservation->setReservationTimeFrame('dinner');
        $reservation->setState('confirmed');
        $reservation->setPeople(10);
        $reservation->setComment('Secondo Update');
        $fExtra=new FExtra(FDatabase::getInstance());
        $newExtra=$fExtra->read(2);
        $reservation->setExtras([$newExtra]);
        $result=$fReservation->update($reservation);

        if ($result) {
            echo "Prenotazione aggiornata correttamente.\n";
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

function testreadReservationsByUserId(): void {
    $existingUserId = 1;
    echo "\nTest 4: Caricamento prenotazioni dell'utente tramite suo ID\n";

    try {
        $fReservation = new FReservation(FDatabase::getInstance());
        $reservations = $fReservation->readReservationsByUserId($existingUserId);

        if (count($reservations) === 0) {
            echo "Nessuna prenotazione trovata.\n";
            echo "Test 4: FALLITO\n";
            return;
        }

        foreach ($reservations as $reservation) {
            if (!($reservation instanceof EReservation)) {
                echo "Errore: elemento non valido nell'array.\n";
                echo "Test 4: FALLITO\n";
                return;
            }

            // Stampa con json_encode come nell'altro test
            echo "Prenotazione:\n" . json_encode($reservation, JSON_PRETTY_PRINT) . "\n";
            echo str_repeat("-", 40) . "\n";
        }

        echo count($reservations) . " prenotazioni trovate e tutte valide.\n";
        echo "Test 4: PASSATO\n";

    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

function testreadReservationsByTableId(): void {
    $existingTableId = 2;
    echo "\nTest 4: Caricamento prenotazioni tramite Id di un tavolo\n";

    try {
        $fReservation = new FReservation(FDatabase::getInstance());
        $reservations = $fReservation->readReservationsByUserId($existingTableId);

        if (count($reservations) === 0) {
            echo "Nessuna prenotazione trovata.\n";
            echo "Test 4: FALLITO\n";
            return;
        }

        foreach ($reservations as $reservation) {
            if (!($reservation instanceof EReservation)) {
                echo "Errore: elemento non valido nell'array.\n";
                echo "Test 4: FALLITO\n";
                return;
            }

            // Stampa con json_encode come nell'altro test
            echo "Prenotazione:\n" . json_encode($reservation, JSON_PRETTY_PRINT) . "\n";
            echo str_repeat("-", 40) . "\n";
        }

        echo count($reservations) . " prenotazioni trovate e tutte valide.\n";
        echo "Test 4: PASSATO\n";

    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

function testGetAvalibleTables() {
    echo "Testing dei tavoli disponibili";
    $fReservation=new FReservation(FDatabase::getInstance());
    $avalibleTables=$fReservation->getAvaliableTables('2025-06-20', 'lunch', 6);

    echo (json_encode($avalibleTables));
}
















//testCreateReservation();
//testReadReservation();
//testUpdateReservation();
//testreadReservationsByUserId();
//testreadReservationsByTableId(); //Il suo funzionamento implica il funzionamento anche di readRes.ByRoomId :)
testGetAvalibleTables();