<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FReservation;
use Entity\EReservation;
use Entity\EUser;
use Entity\ERoom;
use Entity\ETable;
use Entity\TimeFrame;


/**
 * Funzione per creare un oggetto EReservation di test.
 *
 * @return EReservation L'oggetto di test.
 */
function getTestReservationData(): EReservation
{
    return new EReservation(
        null,
        1,
        5,
        null,
        new DateTime('2025-12-13'),
        new DateTime('2025-12-25'),
        TimeFrame::PRANZO,
        'confirmed',
        40.40,
        4,
        'allergiaTest'
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
    $idKnown=2; //ID DA CARICARE
    echo "\nTest 2: Caricamento Prenotazione tramite il suo ID\n";
    try {
        $fReservation = new FReservation(FDatabase::getInstance());
        $reservation = $fReservation->read($idKnown);

        if ($reservation instanceof EReservation) {
            echo "Prenotazione caricata correttamente: " . json_encode($reservation) . "\n";
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




//testCreateReservation();
testReadReservation();