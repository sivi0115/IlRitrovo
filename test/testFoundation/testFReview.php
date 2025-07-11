<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FReview;
use Entity\EReview;
use Entity\EReply;
use Foundation\FReply;

/**
 * Funzione per creare un oggetto EReview di test.
 *
 * @return EReview L'oggetto di test.
 */
function getTestReviewData(): EReview
{
    return new EReview (
    1,
    null,
    5,
    "Recensione Di Prova",
    new DateTime('2025-12-27'),
    );
}

/**
 * Test: Inserimento di una nuova Review.
 */
function testInsertReview(): void
{
    echo "\nTest 1: Inserimento di una nuova Review\n";
    try {
        $fReview = new FReview(FDatabase::getInstance());
        $testReview = getTestReviewData();
        $insertedId = $fReview->create($testReview);

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
 * Test: Caricamento di una Review tramite ID della Review stessa.
 */
function testReadReview(): void
{
    $idKnown=1; //ID DA CARICARE DELLA REVIEW
    echo "\nTest 2: Caricamento Review tramite ID\n";
    try {
        $fReview = new FReview(FDatabase::getInstance());
        $review = $fReview->read($idKnown);

        if ($review instanceof EReview) {
            echo "Review caricata correttamente: " . json_encode($review) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Review non trovata.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Test: Aggiornamento di una Review.
 */
function testUpdateReview(): void
{
    $existingId=1; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di una Review\n";
    try {
        $fReview = new FReview(FDatabase::getInstance());
        $review = $fReview->read($existingId);
        if (!$review) {
            echo "ERRORE: extra con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $review->setStars(3);
        $review->setBody("Corpo della recensione: \n MIAO w i gatetos");
        $result=$fReview->update($review);

        if ($result) {
            echo "Review aggiornata correttamente.\n";
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
 * Test: Cancellazione di una review dal suo ID.
 */
function testDeleteReview(): void
{
    $idToDelete=3; //ID DA ELIMINARE
    try {
        $fReview = new FReview(FDatabase::getInstance());
        $deleted = $fReview->delete($idToDelete);

        if ($deleted) {
            echo "Review cancellata correttamente.\n";
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
 * Test: Verifica esistenza di una Review dal suo id.
 */
function testExistsReview(): void
{
    $existId=4; //ID DA VERIFICARE
    try {
        $fReview = new FReview(FDatabase::getInstance());
        $exists = $fReview->exists($existId);

        if ($exists) {
            echo "La Review esiste.\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "La Review non esiste.\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

/**
 * Test: Funzione che associa una reply ad una review.
 */
function testAddReplyToReview(): void
{
    $existingIdReplyToAssociate = 3; // ID della Reply da associare
    $idReview = 4; // ID della Review alla quale associare la Reply

    echo "\nTest 3: Associazione di una Reply ad una Review\n";

    try {
        $fReview = new FReview(FDatabase::getInstance());

        // 1. Chiamo il metodo da testare
        $result = $fReview->addReplyToReview($idReview, $existingIdReplyToAssociate);

        // 2. Verifico che abbia restituito true
        if ($result) {
            // 3. Controllo che la review sia aggiornata nel DB
            $updatedReview = $fReview->read($idReview);

            if ($updatedReview && $updatedReview->getIdReply() === $existingIdReplyToAssociate) {
                echo "Reply associata correttamente alla Review.\n";
                echo "Test 3: PASSATO\n";
            } else {
                echo "Verifica fallita: idReply non aggiornato correttamente.\n";
                echo "Test 3: FALLITO\n";
            }
        } else {
            echo "Associazione fallita.\n";
            echo "Test 3: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 3: FALLITO\n";
    }
}

/**
 * Test: Caricamento di una Review tramite ID di un utente.
 */
function testReadReviewByUserId(): void
{
    $idKnown=1; //ID DELL'UTENTE DA CARICARE DELLA REVIEW
    echo "\nTest 2: Caricamento Review tramite ID utente\n";
    try {
        $fReview = new FReview(FDatabase::getInstance());
        $review = $fReview->readReviewByUserId($idKnown);

        if ($review instanceof EReview) {
            echo "Review caricata correttamente: " . json_encode($review) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Review non trovata.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Funzione per caricare TUTTE le Review dal db
 */
function testReadAllReviews(): void
{
    echo "\nTest: Caricamento di tutte le reviews\n";
    try {
        $fReview = new FReview(FDatabase::getInstance());
        $allReviews = $fReview->readAllReviews();

        echo "Totale reviews caricate: " . count($allReviews) . "\n";
        echo "Dettagli: " . json_encode($allReviews, JSON_PRETTY_PRINT) . "\n";
        echo "Test: PASSATO\n";
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test: FALLITO\n";
    }
}













//testInsertReview();
//testReadReview();
//testUpdateReview();
//testDeleteReview();
//testExistsReview();
//testAddReplyToReview();
//testReadReviewByUserId();
testReadAllReviews();



