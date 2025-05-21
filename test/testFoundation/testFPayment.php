<?php

// Usare il percorso relativo per includere autoload.php
require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FPayment;
use Foundation\FDatabase;
use Entity\EPayment;
use Entity\StatoPagamento;

/**
 * Funzione per creare un oggetto EPayment di test.
 *
 * @return EPayment L'oggetto di test.
 */
function getTestPaymentData(): EPayment
{
    return new EPayment(
        null,                // ID (sarà impostato dal database)
        (int) 6,          // ID della credit card
        (int) 3,         // ID della reservation (RICONTROLLALO)
        (float) 50.50,                 // Totale da pagare
        new DateTime('2025-12-13'),     //timestamp della data in cui avviene questo pagamento
        StatoPagamento::ANNULLATO       //lo stato del pagamento

    );
}

/**
 * Test: Inserimento di un nuovo payment.
 */
function testInsertPayment(): void
{
    global $insertedId;
    echo "\nTest 1: Inserimento di un nuovo payment\n";
    try {
        $fPayment = new FPayment(FDatabase::getInstance());
        $testPayment = getTestPaymentData();
        $insertedId = $fPayment->create($testPayment);

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
 * Test: Caricamento di un payment tramite ID.
 */
function testLoadPaymentById(): void
{
    $idKnown=1; //RICONTROLLALO
    echo "\nTest 2: Caricamento payment tramite ID\n";
    try {
        $fPayment = new FPayment(FDatabase::getInstance());
        $payment = $fPayment->read($idKnown);

        if ($payment instanceof EPayment) {
            echo "Payment caricato correttamente: " . json_encode($payment) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Payment non trovato.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Test: Aggiornamento di un payment.
 */
function testUpdatePayment(): void
{
    $existingId=1; //RICONTROLLALO
    echo "\nTest 3: Aggiornamento di un payment\n";
    try {
        $fPayment = new FPayment(FDatabase::getInstance());
        $payment = $fPayment->read($existingId);
        if (!$payment) {
            echo "ERRORE: payment con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $payment->setTotal(60.60);
        $payment->setCreationTime(new DateTime('2025-12-14'));
        $payment->setState(StatoPagamento::COMPLETATO);
        $result=$fPayment->update($payment);

        if ($result) {
            echo "Payment aggiornato correttamente.\n";
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
