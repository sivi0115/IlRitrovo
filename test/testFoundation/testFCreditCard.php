<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FCreditCard;
use Foundation\FDatabase;
use Entity\ECreditCard;

/**
 * Crea una carta di credito di test.
 *
 * @return ECreditCard
 * @throws Exception
 */
function getTestCreditCardData(): ECreditCard
{
    return new ECreditCard(
        1,
        'holderTesting',
        '111222333444555777',
        123,
        new DateTime('2025-12-13'),
        'Visa',
        1
    );
}

/**
 * Testa l'inserimento di una carta di credito.
 *
 * @param int $userId
 * @return string
 */
function testCreateCreditCard(): void
{
    echo "\nTest 1: Inserimento di una nuova Carta di Credito\n";
    try {
        $fCreditCard = new FCreditCard(FDatabase::getInstance());
        $testCreditCard = getTestCreditCardData();
        $insertedId = $fCreditCard->create($testCreditCard);

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
 * Testa il caricamento di una carta di credito.
 *
 * @param string $cardNumber
 * @param int $userId
 */
/**
 * Test: Caricamento di una carta tramite il suo ID.
 */
function testReadCreditCard(): void
{
    $idKnown=4; //ID DA CARICARE
    echo "\nTest 2: Caricamento carta tramite ID\n";
    try {
        $fCreditCard = new FCreditCard(FDatabase::getInstance());
        $creditCard = $fCreditCard->read($idKnown);

        if ($creditCard instanceof ECreditCard) {
            echo "Carta caricata correttamente: " . json_encode($creditCard) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Carta non trovata.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}


/**
 * Testa l'aggiornamento di una carta di credito.
 */
function testUpdateCreditCard(): void
{
    $existingId=5; //NUMERO DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di una carta di credito\n";
    try {
        $fCreditCard = new FCreditCard(FDatabase::getInstance());
        $creditCard = $fCreditCard->read($existingId);
        if (!$creditCard) {
            echo "ERRORE: carta con ID $existingId non trovata";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $creditCard->setNumber('9999999923456876');
        $creditCard->setHolder('holderMODIFIED');
        $creditCard->setCvv(456);
        $creditCard->setExpiration(new DateTime('2025-12-14'));
        $creditCard->setType('Mastercard');
        $result=$fCreditCard->update($creditCard);

        if ($result) {
            echo "Carta aggiornata correttamente.\n";
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
 * Testa l'eliminazione di una carta di credito.
 *
 * @param string $cardNumber
 * @param int $userId
 */
function testDeleteCreditCard(): void
{
    $idToDelete=5; //ID DA ELIMINARE
    try {
        $fCreditCard = new FCreditCard(FDatabase::getInstance());
        $creditCard = $fCreditCard->delete($idToDelete);

        if ($creditCard) {
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
 * Testa il caricamento delle carte di credito dall'Id dell'utente.
 */
function testReadCreditCardsByUser(): void
{
    $idKnown=1; //ID DELL'UTENTE DA CARICARE
    echo "\nTest 2: Caricamento carte tramite ID utente\n";
    try {
        $fCreditCard = new FCreditCard(FDatabase::getInstance());
        $creditCards = $fCreditCard->readCreditCardsByUser($idKnown);

        if (is_array($creditCards) && count($creditCards) > 0) {
            echo "Carte caricate correttamente: " . json_encode($creditCards) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Carta non trovata.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Testa la funzione `existsCreditCard`.
 *
 * @param string $cardNumber
 * @param int $userId
 */
function testExistsCreditCard(string $cardNumber, int $userId): void
{
    echo "\n[TEST] Verifica esistenza carta di credito\n";

    try {
        $exists = FCreditCard::exists($cardNumber, $userId);

        if ($exists) {
            echo "La carta di credito esiste nel database.\n";
        } else {
            echo "La carta di credito non esiste nel database.\n";
        }
    } catch (Exception $e) {
        echo "Errore durante il test: " . $e->getMessage() . "\n";
    }
}

/**
 * Testa la funzione `maskCreditCardNumber`.
 */
function testMaskCreditCardNumber(): void
{
    echo "\n[TEST] Mascheramento numero carta di credito\n";

    $cardNumber = '111222333444555666';
    $masked = FCreditCard::maskCreditCardNumber($cardNumber);

    echo "Numero originale: $cardNumber\n";
    echo "Numero mascherato: $masked\n";
}


// Esecuzione dei test
echo "Esecuzione dei test per FCreditCard...\n";

testCreateCreditCard();
//testReadCreditCard();
//testUpdateCreditCard();
//testDeleteCreditCard();
//testReadCreditCardsByUser();
//testMaskCreditCardNumber();


/**$c1=new ECreditCard(4,'holderTesting', '1234567891234567', 123, new DateTime('2025-12-13'), 'Visa', 1);
print($c1->getNumber());*/
