<?php

// Usare il percorso relativo per includere autoload.php
require_once __DIR__ . '/../../vendor/autoload.php';

use Entity\EEvent;
use Foundation\FEvent;

/**
 * Funzione principale che esegue i test
 */
function runTests(): void
{
    echo "Esecuzione dei test su FEventType...\n";

    // Test 1: Inserimento di un tipo di evento valido
    echo "\nTest 1: Inserimento di un nuovo tipo di evento valido\n";
    try {
        $eventType = new EEvent(null, 'Concert');
        $insertedId = FEvent::storeEvent($eventType);
        if ($insertedId > 0) {
            echo "Inserimento riuscito con ID: $insertedId\nTest 1: PASSATO\n";
        } else {
            echo "Test 1: FALLITO - Nessun ID restituito\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\nTest 1: FALLITO\n";
    }

    // Test 2: Caricamento di un tipo di evento
    echo "\nTest 2: Caricamento del tipo di evento inserito\n";
    try {
        $eventType = FEvent::loadEvent($insertedId);
        if ($eventType !== null && $eventType->getName() === 'Concert') {
            echo "Test 2: PASSATO - Tipo di evento caricato correttamente\n";
        } else {
            echo "Test 2: FALLITO - Tipo di evento non caricato correttamente\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\nTest 2: FALLITO\n";
    }

    // Test 3: Aggiornamento di un tipo di evento
    echo "\nTest 3: Aggiornamento del tipo di evento\n";
    try {
        $eventType->setName('Updated Concert');
        if (FEvent::updateEvent($eventType)) {
            $updatedEventType = FEvent::loadEvent($eventType->getIdEvent());
            if ($updatedEventType !== null && $updatedEventType->getName() === 'Updated Concert') {
                echo "Test 3: PASSATO - Tipo di evento aggiornato correttamente\n";
            } else {
                echo "Test 3: FALLITO - Tipo di evento aggiornato non caricato correttamente\n";
            }
        } else {
            echo "Test 3: FALLITO - Aggiornamento non riuscito\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\nTest 3: FALLITO\n";
    }

    // Test 4: Eliminazione di un tipo di evento
    echo "\nTest 4: Eliminazione del tipo di evento\n";
    try {
        if (FEvent::deleteEvent($eventType->getIdEvent())) {
            $deletedEventType = FEvent::loadEvent($eventType->getIdEvent());
            if ($deletedEventType === null) {
                echo "Test 4: PASSATO - Tipo di evento eliminato correttamente\n";
            } else {
                echo "Test 4: FALLITO - Tipo di evento ancora presente\n";
            }
        } else {
            echo "Test 4: FALLITO - Eliminazione fallita\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\nTest 4: FALLITO\n";
    }

    // Test 5: Caricamento di tutti i tipi di evento
    echo "\nTest 5: Caricamento di tutti i tipi di evento\n";
    try {
        FEvent::storeEvent(new EEvent(null, 'Workshop'));
        FEvent::storeEvent(new EEvent(null, 'Seminar'));
        $allEventTypes = FEvent::loadAllEvents();
        if (count($allEventTypes) >= 2) {
            echo "Test 5: PASSATO - Tutti i tipi di evento caricati correttamente\n";
        } else {
            echo "Test 5: FALLITO - Mancano dei tipi di evento\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\nTest 5: FALLITO\n";
    }

    // Test 6: Esistenza di un tipo di evento
    echo "\nTest 6: Verifica dell'esistenza di un tipo di evento\n";
    try {
        // Creiamo un nuovo tipo di evento per il test
        $newEventType = new EEvent(null, 'Temporary Event');
        $tempEventId = FEvent::storeEvent($newEventType);

        // Ora verifichiamo se esiste
        $exists = FEvent::existsEvents($tempEventId);
        if ($exists) {
            echo "Test 6: PASSATO - Il tipo di evento esiste\n";
        } else {
            echo "Test 6: FALLITO - Il tipo di evento non esiste\n";
        }

        // Pulizia: eliminare l'evento temporaneo
        FEvent::deleteEvent($tempEventId);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\nTest 6: FALLITO\n";
    }

}

// Eseguire i test
try {
    runTests();
} catch (Exception $e) {

}
