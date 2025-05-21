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
        null,
        'holderTesting',
        '1234567891234567',
        123,
        new DateTime('2025-12-13'),
        'Visa',
        4
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
    $idKnown=2; //ID DA CARICARE
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
    $existingId=2; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di una carta di credito\n";
    try {
        $fCreditCard = new FCreditCard(FDatabase::getInstance());
        $creditCard = $fCreditCard->read($existingId);
        if (!$creditCard) {
            echo "ERRORE: extra con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $creditCard->setNumber('9999999923456876');
        $creditCard->setHolder('holderMODIFIED');
        $creditCard->setCvv(456);
        $creditCard->setExpiration(new DateTime('2025-12-14'));
        $creditCard->setType('Masterard');
        $result=$fCreditCard->update($creditCard);

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
 * Testa l'eliminazione di una carta di credito.
 *
 * @param string $cardNumber
 * @param int $userId
 */
function testDeleteCreditCard(string $cardNumber, int $userId): void
{
    echo "\n[TEST] Eliminazione di una carta di credito\n";

    try {
        $result = FCreditCard::delete($cardNumber, $userId);

        if ($result) {
            echo "Carta di credito eliminata correttamente.\n";
        } else {
            echo "Eliminazione fallita.\n";
        }
    } catch (Exception $e) {
        echo "Errore durante il test: " . $e->getMessage() . "\n";
    }
}

/**
 * Testa il caricamento di tutte le carte di credito di un utente.
 *
 * @param int $userId
 */
function testLoadAllCreditCards(int $userId): void
{
    echo "\n[TEST] Caricamento di tutte le carte di credito di un utente\n";

    try {
        $creditCards = FCreditCard::loadCreditCardsByUser($userId);

        if (!empty($creditCards)) {
            echo "Carte di credito caricate correttamente:\n";
            print_r($creditCards);
        } else {
            echo "Nessuna carta di credito trovata.\n";
        }
    } catch (Exception $e) {
        echo "Errore durante il test: " . $e->getMessage() . "\n";
    }
}

/**
 * Testa l'impostazione della carta predefinita.
 *
 * @param int $cardId
 * @param int $userId
 */
function testSetDefaultCreditCard(int $cardId, int $userId): void
{
    echo "\n[TEST] Impostazione della carta di credito predefinita\n";

    try {
        $result = FCreditCard::setDefault($cardId, $userId);

        if ($result) {
            echo "Carta di credito impostata come predefinita correttamente.\n";
        } else {
            echo "Impostazione predefinita fallita.\n";
        }
    } catch (Exception $e) {
        echo "Errore durante il test: " . $e->getMessage() . "\n";
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

    $cardNumber = '4111111111111111';
    $masked = FCreditCard::maskCreditCardNumber($cardNumber);

    echo "Numero originale: $cardNumber\n";
    echo "Numero mascherato: $masked\n";
}

/**
 * Testa la funzione `isValidCVV`.
 */
function testIsValidCVV(): void
{
    echo "\n[TEST] Validazione CVV\n";

    $validCVV = FCreditCard::isValidCVV('123', 'VISA');
    $invalidCVV = FCreditCard::isValidCVV('12A', 'VISA');

    echo "CVV valido ('123' per VISA): " . ($validCVV ? "Corretto\n" : "Errato\n");
    echo "CVV non valido ('12A' per VISA): " . ($invalidCVV ? "Corretto\n" : "Errato\n");
}

/**
 * Testa la funzione `isValidExpirationDate`.
 */
function testIsValidExpirationDate(): void
{
    echo "\n[TEST] Validazione data di scadenza\n";

    $validDate = FCreditCard::isValidExpirationDate('12/25');
    $invalidDate = FCreditCard::isValidExpirationDate('01/20');

    echo "Data valida ('12/25'): " . ($validDate ? "Corretto\n" : "Errato\n");
    echo "Data non valida ('01/20'): " . ($invalidDate ? "Corretto\n" : "Errato\n");
}

// Esecuzione dei test
echo "Esecuzione dei test per FCreditCard...\n";

//testCreateCreditCard();
//testReadCreditCard();
testUpdateCreditCard();