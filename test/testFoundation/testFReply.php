<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FReply;
use Entity\EReply;

/**
 * Funzione per creare un oggetto EExtra di test.
 *
 * @return EReply L'oggetto di test.
 */
function getTestExtraData(): EReply
{
    return new EReply(
        null,
        new DateTime('2025-12-13'),
        'recensioneDiTestingNuova',
    );
}

/**
 * Funzione per testare l'inserimento di una nuova reply nel db
 */
function testInsertReply(): void
{
    echo "\nTest 1: Inserimento di una nuova Reply\n";
    try {
        $fReply = new FReply(FDatabase::getInstance());
        $testReply = getTestExtraData();
        $insertedId = $fReply->create($testReply);

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
function testReadReply(): void
{
    $idKnown=1; //ID DA CARICARE
    echo "\nTest 2: Caricamento Reply tramite ID\n";
    try {
        $fReply = new FReply(FDatabase::getInstance());
        $reply = $fReply->read($idKnown);

        if ($reply instanceof EReply) {
            echo "Reply caricata correttamente: " . json_encode($reply) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Reply non trovata.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Test: Aggiornamento di una Reply.
 */
function testUpdateReply(): void
{
    $existingId=1; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di una Reply\n";
    try {
        $fReply = new FReply(FDatabase::getInstance());
        $reply = $fReply->read($existingId);
        if (!$reply) {
            echo "ERRORE: Reply con ID $existingId non trovata";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $reply->setDateReply(new DateTime('2025-12-25'));
        $reply->setBody('Recensione Modificata');
        $result=$fReply->update($reply);

        if ($result) {
            echo "Reply aggiornata correttamente.\n";
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
 * Test: Cancellazione di una Reply.
 */
function testDeleteReply(): void
{
    $idToDelete=1; //ID DA ELIMINARE
    try {
        $fReply = new FReply(FDatabase::getInstance());
        $deleted = $fReply->delete($idToDelete);

        if ($deleted) {
            echo "Reply cancellata correttamente.\n";
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
 * Test: Verifica esistenza di una Reply.
 */
function testExistsReply(): void
{
    $existId=1; //ID DA VERIFICARE
    try {
        $fReply = new FReply(FDatabase::getInstance());
        $exists = $fReply->exists($existId);

        if ($exists) {
            echo "La Reply esiste.\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "La reply non esiste.\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}
































//testInsertReply();
//testReadReply();
//testUpdateReply();
//testDeleteReply();
testExistsReply();
