<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FAdmin;
use Foundation\FDatabase;
use Entity\EAdmin;

function runTests()
{
    $database = FDatabase::getInstance();
    $fAdmin = new FAdmin($database);

    // Test 1: Salvataggio di un admin valido
    echo "Test 1: Salvataggio di un admin valido\n";
    try {
        $admin = new EAdmin(null, "adminUser", "Mario", "Rossi", new DateTime("1990-01-01"), "+391234567890", null, "mario.rossi@example.com", "securePassword@123", "Manager");
        $result = $fAdmin->saveAdmin($admin);
        if ($result) {
            echo "Admin salvato con successo.\n";
            echo "Test 1: PASSATO\n";
        } else {
            echo "Errore durante il salvataggio.\n";
            echo "Test 1: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 1: FALLITO\n";
    }

    // Test 2: Tentativo di salvataggio con dati incompleti (username vuoto)
    echo "\nTest 2: Tentativo di salvataggio con dati incompleti\n";
    try {
        $admin = new EAdmin(null, "", "Giovanni", "Verdi", new DateTime("1985-05-15"), "+390987654321", null, "giovanni.verdi@example.com", "anotherPassword@321", "Developer");
        $fAdmin->saveAdmin($admin);
        echo "Admin salvato con dati incompleti.\n";
        echo "Test 2: FALLITO\n";
    } catch (Exception $e) {
        echo "Errore atteso: " . $e->getMessage() . "\n";
        echo "Test 2: PASSATO\n";
    }

    // Test 3: Caricamento di un admin tramite ID
    echo "\nTest 3: Caricamento di un admin tramite ID\n";
    try {
        $adminId = 1; // Replace with an existing ID for accurate testing
        $loadedAdmin = $fAdmin->loadAdminById($adminId);
        if ($loadedAdmin) {
            echo "Admin caricato con successo: " . print_r($loadedAdmin, true) . "\n";
            echo "Test 3: PASSATO\n";
        } else {
            echo "Admin non trovato per ID: $adminId\n";
            echo "Test 3: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 3: FALLITO\n";
    }

    // Test 4: Caricamento di un admin tramite username
    echo "\nTest 4: Caricamento di un admin tramite username\n";
    try {
        $username = "adminUser"; // Replace with an existing username for accurate testing
        $loadedAdmin = $fAdmin->loadByUsername($username);
        if ($loadedAdmin) {
            echo "Admin caricato con successo: " . print_r($loadedAdmin, true) . "\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "Admin non trovato per username: $username\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }

    // Test 5: Aggiornamento di un admin
    echo "\nTest 5: Aggiornamento di un admin\n";
    try {
        $adminId = 1; // Replace with an existing ID for accurate testing
        $updateData = [
            'name' => 'Mario Updated',
            'email' => 'mario.updated@example.com',
        ];
        $result = $fAdmin->updateAdminById($adminId, $updateData);
        if ($result) {
            echo "Admin aggiornato con successo.\n";
            echo "Test 5: PASSATO\n";
        } else {
            echo "Errore durante l'aggiornamento.\n";
            echo "Test 5: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 5: FALLITO\n";
    }

    /**
    // Test 6: Eliminazione di un admin
    echo "\nTest 6: Eliminazione di un admin\n";
    try {
        $adminId = 1; // Replace with an existing ID for accurate testing
        $result = $fAdmin->deleteAdmin($adminId);
        if ($result) {
            echo "Admin eliminato con successo.\n";
            echo "Test 6: PASSATO\n";
        } else {
            echo "Errore durante l'eliminazione.\n";
            echo "Test 6: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 6: FALLITO\n";
    }
     * */


    // Test 7: Caricamento di tutte le location
    echo "\nTest 7: Caricamento di tutte le location\n";
    try {
        $locations = $fAdmin->readallLocation();
        if (!empty($locations)) {
            echo "Location caricate con successo: " . print_r($locations, true) . "\n";
            echo "Test 7: PASSATO\n";
        } else {
            echo "Nessuna location trovata.\n";
            echo "Test 7: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 7: FALLITO\n";
    }

    // Test 8: Recupero di stanze per una location
    echo "\nTest 8: Recupero di stanze per una location\n";
    try {
        $locationId = 1; // Replace with an existing location ID
        $rooms = $fAdmin->getRoomsByLocation($locationId);
        if (!empty($rooms)) {
            echo "Stanze caricate con successo: " . print_r($rooms, true) . "\n";
            echo "Test 8: PASSATO\n";
        } else {
            echo "Nessuna stanza trovata per la location.\n";
            echo "Test 8: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 8: FALLITO\n";
    }

    // Test 9: Recupero dei servizi per una location
    echo "\nTest 9: Recupero dei servizi per una location\n";
    try {
        $locationId = 1; // Replace with an existing location ID
        $services = $fAdmin->getServicesByLocation($locationId);
        if (!empty($services)) {
            echo "Servizi caricati con successo: " . print_r($services, true) . "\n";
            echo "Test 9: PASSATO\n";
        } else {
            echo "Nessun servizio trovato per la location.\n";
            echo "Test 9: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 9: FALLITO\n";
    }

    // Test 10: Bannare una recensione
    echo "\nTest 10: Bannare una recensione\n";
    try {
        $reviewId = 1; // Replace with an existing review ID
        $result = $fAdmin->BanReview($reviewId);
        if ($result) {
            echo "Recensione bannata/eliminata con successo.\n";
            echo "Test 10: PASSATO\n";
        } else {
            echo "Errore durante il ban/eliminazione della recensione.\n";
            echo "Test 10: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 10: FALLITO\n";
    }

    // Test 11: Caricamento di un admin inesistente
    echo "\nTest 11: Caricamento di un admin inesistente\n";
    try {
        $username = "nonEsiste";
        $loadedAdmin = $fAdmin->loadByUsername($username);
        if ($loadedAdmin) {
            echo "Test 7: FALLITO (non doveva trovare un admin)\n";
        } else {
            echo "Admin non trovato, come previsto.\n";
            echo "Test 7: PASSATO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 7: FALLITO\n";
    }

}

// Esegui i test
runTests();
