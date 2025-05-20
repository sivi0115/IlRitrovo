<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FCreditCard;
use Foundation\FDatabase;
use Entity\ECreditCard;

/**
 * Inserisce un utente di test e restituisce il suo ID.
 *
 * @return int
 * @throws Exception
 */
function insertTestUser(): int
{
    echo "\n[TEST] Inserimento utente di test\n";

    $userData = [
        'username' => 'testUser1',
        'name' => 'John',
        'surname' => 'Doe',
        'birthDate' => '1985-05-15',
        'phone' => '1234567890',
        'image' => null,
        'email' => 'testuser@example.com',
        'password' => 'passwordHash',
        'ban' => 0,
        'motivation' => null,
    ];

    $db = FDatabase::getInstance();

    try {
        $userId = $db->insert('user', $userData);

        if (!$userId) {
            throw new Exception("Errore durante l'inserimento dell'utente");
        }

        echo "Utente inserito correttamente con ID: $userId\n";
        return $userId;
    } catch (Exception $e) {
        echo "Errore durante l'inserimento dell'utente: " . $e->getMessage() . "\n";
        throw $e;
    }
}

/**
 * Crea una carta di credito di test.
 *
 * @return ECreditCard
 * @throws Exception
 */
function createTestCreditCard(): ECreditCard
{
    return new ECreditCard(
        null,
        '4111111111111111',
        '123',
        new DateTime('2025-12-31'),
        'Test Holder',
        'Visa'
    );
}

/**
 * Testa l'inserimento di una carta di credito.
 *
 * @param int $userId
 * @return string
 */
function testStoreCreditCard(int $userId): string
{
    echo "\n[TEST] Inserimento di una carta di credito\n";

    try {
        $creditCard = createTestCreditCard();
        $result = FCreditCard::storeCreditCard($creditCard, $userId);

        if ($result) {
            echo "Carta di credito inserita correttamente. ID: " . $creditCard->getIdCreditCard() . "\n";
            return $creditCard->getNumber();
        } else {
            echo "Inserimento della carta fallito.\n";
        }
    } catch (Exception $e) {
        echo "Errore durante il test: " . $e->getMessage() . "\n";
    }

    return '';
}


/**
 * Testa il caricamento di una carta di credito.
 *
 * @param string $cardNumber
 * @param int $userId
 */
function testLoadCreditCard(string $cardNumber, int $userId): void
{
    echo "\n[TEST] Caricamento di una carta di credito\n";

    try {
        $creditCard = FCreditCard::loadCreditCard($cardNumber, $userId);

        if ($creditCard instanceof ECreditCard) {
            echo "Carta di credito caricata correttamente. ID: " . $creditCard->getIdCreditCard() . "\n";
            print_r($creditCard);
        } else {
            echo "Carta di credito non trovata.\n";
        }
    } catch (Exception $e) {
        echo "Errore durante il test: " . $e->getMessage() . "\n";
    }
}


/**
 * Testa l'aggiornamento di una carta di credito.
 *
 * @param string $cardNumber
 * @param int $userId
 */
function testUpdateCreditCard(string $cardNumber, int $userId): void
{
    echo "\n[TEST] Aggiornamento di una carta di credito\n";

    try {
        $creditCard = FCreditCard::loadCreditCard($cardNumber, $userId);

        if ($creditCard instanceof ECreditCard) {
            $creditCard->setCvv('456'); // Cambia il CVV
            $result = FCreditCard::updateCreditCard($creditCard, $userId);

            if ($result) {
                echo "Carta di credito aggiornata correttamente.\n";
            } else {
                echo "Aggiornamento fallito.\n";
            }
        } else {
            echo "Carta di credito non trovata per l'aggiornamento.\n";
        }
    } catch (Exception $e) {
        echo "Errore durante il test: " . $e->getMessage() . "\n";
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
        $result = FCreditCard::deleteCreditCard($cardNumber, $userId);

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
        $creditCards = FCreditCard::loadCreditCardByUser($userId);

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
        $exists = FCreditCard::existsCreditCard($cardNumber, $userId);

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

try {
    // Step 1: Inserisci un utente di test
    $userId = insertTestUser();

    // Step 2: Testa l'inserimento di una carta di credito
    $cardNumber = testStoreCreditCard($userId);

    if ($cardNumber !== '') {
        // Step 3: Testa il caricamento della carta
        testLoadCreditCard($cardNumber, $userId);

        // Step 4: Testa l'aggiornamento della carta
        testUpdateCreditCard($cardNumber, $userId);

        // Step 5: Testa l'esistenza della carta
        testExistsCreditCard($cardNumber, $userId);

        // Step 6: Testa il caricamento di tutte le carte
        testLoadAllCreditCards($userId);

        // Step 7: Testa il mascheramento del numero
        testMaskCreditCardNumber();

        // Step 8: Testa la validazione del CVV
        testIsValidCVV();

        // Step 9: Testa la validazione della data di scadenza
        testIsValidExpirationDate();

        // Step 10: Testa l'eliminazione della carta
        //testDeleteCreditCard($cardNumber, $userId);
    }
} catch (Exception $e) {
    echo "Errore durante l'esecuzione dei test: " . $e->getMessage() . "\n";
}