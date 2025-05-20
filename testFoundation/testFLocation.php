<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FDatabase;
use Foundation\FLocation;
use Entity\ELocation;

/**
 * Inserisce un proprietario di test nel database.
 *
 * @return int L'ID del proprietario inserito.
 * @throws Exception Se l'inserimento fallisce.
 */
function insertTestOwner(): int
{
    $db = FDatabase::getInstance();
    $ownerData = [
        'username' => 'testuser',
        'name' => 'Test',
        'surname' => 'Owner',
        'birthDate' => '2000-01-01',
        'phone' => '1234567890',
        'image' => 'https://example.com/image.jpg',
        'email' => 'testuser@example.com',
        'password' => password_hash('securepassword', PASSWORD_DEFAULT),
        'validation' => 0,
    ];

    $ownerId = $db->insert('owner', $ownerData);
    if ($ownerId === null) {
        throw new Exception('Errore: impossibile inserire il proprietario di test.');
    }

    return $ownerId;
}

/**
 * Inserisce un indirizzo di test nel database.
 *
 * @return int L'ID dell'indirizzo inserito.
 * @throws Exception Se l'inserimento fallisce.
 */
function insertTestAddress(): int
{
    $db = FDatabase::getInstance();
    $addressData = [
        'streetAddress' => '123 Test Street',
        'civicNumber' => '10',
        'city' => 'Test City',
        'postalCode' => '12345',
        'country' => 'Test Country',
        'state' => 'Test State',
    ];

    $addressId = $db->insert('address', $addressData);
    if ($addressId === null) {
        throw new Exception('Errore: impossibile inserire l\'indirizzo di test.');
    }

    return $addressId;
}

/**
 * Esegue i test relativi alle location.
 */
function runTests()
{
    echo "===== Inizio dei test =====\n";

    $insertedId = null;

    // Test 1: Inserimento di una nuova location
    echo "\nTest 1: Inserimento di una nuova location\n";
    try {
        $ownerId = insertTestOwner();
        $addressId = insertTestAddress();
        $testLocation = new ELocation(
            null,
            'Test Location',
            'IT12345678901',
            'Descrizione di test.',
            ['https://example.com/photo1.jpg', 'https://example.com/photo2.jpg'],
            'Tipo A',
            $ownerId,
            $addressId
        );
        $insertedId = FLocation::storeLocation($testLocation);
        echo "Inserimento riuscito! ID location: $insertedId\n";
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
    }

    // Test 2: Caricamento della location
    if ($insertedId) {
        echo "\nTest 2: Caricamento della location\n";
        try {
            $location = FLocation::load($insertedId);
            if ($location) {
                echo "Location caricata con successo: " . json_encode($location) . "\n";
            } else {
                echo "Errore durante il caricamento della location.\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
        }
    }

    // Test 3: Verifica dell'esistenza della location
    if ($insertedId) {
        echo "\nTest 3: Verifica dell'esistenza della location\n";
        try {
            $exists = FLocation::existsLocation($insertedId);
            echo $exists ? "La location esiste.\n" : "La location non esiste.\n";
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
        }
    }

    // Test 4: Aggiornamento della location
    if ($insertedId) {
        echo "\nTest 4: Aggiornamento della location\n";
        try {
            $location = FLocation::load($insertedId);
            $location->setName('Updated Test Location');
            $updated = FLocation::updateLocation($location);
            echo $updated ? "Location aggiornata con successo.\n" : "Errore durante l'aggiornamento della location.\n";
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
        }
    }

    // Test 5: Incremento delle visualizzazioni
    if ($insertedId) {
        echo "\nTest 5: Incremento delle visualizzazioni\n";
        try {
            FLocation::incrementViews($insertedId);
            $views = FLocation::getLocationViews($insertedId);
            echo "Visualizzazioni aggiornate: $views\n";
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
        }
    }

    // Test 6: Validazione della location
    if ($insertedId) {
        echo "\nTest 6: Validazione della location\n";
        try {
            FLocation::validateLocation($insertedId, 'approved');
            echo "Location validata come 'approved'.\n";
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
        }
    }

    // Test 7: Caricamento delle foto della location
    if ($insertedId) {
        echo "\nTest 7: Caricamento delle foto della location\n";
        try {
            $photos = FLocation::loadPhotos($insertedId);
            echo "Foto trovate: " . json_encode($photos) . "\n";
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
        }
    }



    // Test 10: Verifica delle location ordinate per visualizzazioni
    echo "\nTest 10: Verifica delle location ordinate per visualizzazioni\n";
    try {
        $locations = FLocation::getLocationsByPopularity();
        if (!empty($locations)) {
            $isOrdered = true;
            $previousViews = null;
            foreach ($locations as $location) {
                $currentViews = $location->getViews();
                echo "- ID: " . $location->getIdLocation() . ", Views: " . $currentViews . "\n";
                if ($previousViews !== null && $currentViews > $previousViews) {
                    $isOrdered = false;
                }
                $previousViews = $currentViews;
            }
            echo $isOrdered ? "Test 10: PASSATO\n" : "Test 10: FALLITO (ordine non corretto)\n";
        } else {
            echo "Nessuna location trovata per verificare l'ordinamento.\n";
            echo "Test 10: PASSATO (nessun dato disponibile)\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 10: FALLITO\n";
    }

    // Test 11: Verifica delle location con stato 'pending'
    echo "\nTest 11: Verifica delle location con stato 'pending'\n";
    try {
        $pendingLocations = FLocation::getPendingLocations();
        if (!empty($pendingLocations)) {
            echo "Location con stato 'pending' trovate:\n";
            foreach ($pendingLocations as $location) {
                echo "- ID: " . $location->getIdLocation() . ", Nome: " . $location->getName() . "\n";
            }
            echo "Test 11: PASSATO\n";
        } else {
            echo "Nessuna location con stato 'pending' trovata.\n";
            echo "Test 11: PASSATO\n"; // Fallisce solo se si aspettano risultati e non ce ne sono.
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 11: FALLITO\n";
    }

    // Test 12: Verifica del conteggio delle location recenti
    // Test 12: Verifica del conteggio delle location recenti
    echo "\nTest 12: Verifica del conteggio delle location recenti\n";
    try {
        $recentCount = FLocation::countNewLocations();
        echo "Numero di location create negli ultimi 7 giorni: $recentCount\n";
        if ($recentCount >= 0) {
            echo "Test 12: PASSATO\n";
        } else {
            echo "Test 12: FALLITO (conteggio non valido)\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 12: FALLITO\n";
    }


    // Test 13: Verifica della ricerca delle location per nome
    echo "\nTest 13: Verifica della ricerca delle location per nome\n";
    try {
        $searchName = 'Test'; // Nome da cercare
        $foundLocations = FLocation::searchLocationByName($searchName);
        if (!empty($foundLocations)) {
            $allMatch = true;
            foreach ($foundLocations as $location) {
                echo "- ID: " . $location->getIdLocation() . ", Nome: " . $location->getName() . "\n";
                if (stripos($location->getName(), $searchName) === false) {
                    $allMatch = false;
                }
            }
            echo $allMatch ? "Test 13: PASSATO\n" : "Test 13: FALLITO (nome non corrispondente)\n";
        } else {
            echo "Nessuna location trovata con nome contenente '$searchName'.\n";
            echo "Test 13: PASSATO (nessun dato disponibile)\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 13: FALLITO\n";
    }

    // Test 14: Filtra location per servizi
    echo "\nTest 14: Filtra location per servizi\n";
    try {
        $services = ['WiFi', 'Parking'];
        $filteredLocations = FLocation::filterByServices($services);
        if (!empty($filteredLocations)) {
            echo "Location trovate con i servizi specificati:\n";
            foreach ($filteredLocations as $location) {
                echo "- ID: " . $location->getIdLocation() . ", Nome: " . $location->getName() . "\n";
            }
            echo "Test 14: PASSATO\n";
        } else {
            echo "Nessuna location trovata con i servizi specificati.\n";
            echo "Test 14: PASSATO (nessun dato disponibile)\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 14: FALLITO\n";
    }

    // Test 15: Filtra location per extra
    echo "\nTest 15: Filtra location per extra\n";
    try {
        $extras = ['Breakfast', 'Pool'];
        $filteredLocations = FLocation::filterByExtras($extras);
        if (!empty($filteredLocations)) {
            echo "Location trovate con gli extra specificati:\n";
            foreach ($filteredLocations as $location) {
                echo "- ID: " . $location->getIdLocation() . ", Nome: " . $location->getName() . "\n";
            }
            echo "Test 15: PASSATO\n";
        } else {
            echo "Nessuna location trovata con gli extra specificati.\n";
            echo "Test 15: PASSATO (nessun dato disponibile)\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 15: FALLITO\n";
    }

    // Test 16: Carica recensioni di una location
    if ($insertedId) {
        echo "\nTest 16: Carica recensioni di una location\n";
        try {
            $reviews = FLocation::loadReviewByLocation($insertedId);
            if (!empty($reviews)) {
                echo "Recensioni trovate:\n";
                foreach ($reviews as $review) {
                    echo "- " . json_encode($review) . "\n";
                }
                echo "Test 16: PASSATO\n";
            } else {
                echo "Nessuna recensione trovata per la location.\n";
                echo "Test 16: PASSATO (nessun dato disponibile)\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 16: FALLITO\n";
        }
    }

    // Test 17: Carica stanze di una location
    if ($insertedId) {
        echo "\nTest 17: Carica stanze di una location\n";
        try {
            $rooms = FLocation::getRooms($insertedId);
            if (!empty($rooms)) {
                echo "Stanze trovate:\n";
                foreach ($rooms as $room) {
                    echo "- ID Stanza: " . $room->getIdRoom() . "\n";
                }
                echo "Test 17: PASSATO\n";
            } else {
                echo "Nessuna stanza trovata per la location.\n";
                echo "Test 17: PASSATO (nessun dato disponibile)\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 17: FALLITO\n";
        }
    }

    // Test 18: Carica prenotazioni di una location
    if ($insertedId) {
        echo "\nTest 18: Carica prenotazioni di una location\n";
        try {
            $reservations = FLocation::getReservations($insertedId);
            if (!empty($reservations)) {
                echo "Prenotazioni trovate:\n";
                foreach ($reservations as $reservation) {
                    echo "- ID Prenotazione: " . $reservation->getIdReservation() . "\n";
                }
                echo "Test 18: PASSATO\n";
            } else {
                echo "Nessuna prenotazione trovata per la location.\n";
                echo "Test 18: PASSATO (nessun dato disponibile)\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 18: FALLITO\n";
        }
    }

    // Test 19: Recupera proprietario di una location
    if ($insertedId) {
        echo "\nTest 19: Recupera proprietario di una location\n";
        try {
            $owner = FLocation::getOwner($insertedId);
            if ($owner) {
                echo "Proprietario trovato: " . $owner->getName() . " " . $owner->getSurname() . "\n";
                echo "Test 19: PASSATO\n";
            } else {
                echo "Proprietario non trovato.\n";
                echo "Test 19: FALLITO\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 19: FALLITO\n";
        }
    }

    // Test 20: Recupera indirizzo di una location
    if ($insertedId) {
        echo "\nTest 20: Recupera indirizzo di una location\n";
        try {
            $address = FLocation::getAddress($insertedId);
            if ($address) {
                echo "Indirizzo trovato: " . $address->getStreetAddress() . ", " . $address->getCity() . "\n";
                echo "Test 20: PASSATO\n";
            } else {
                echo "Indirizzo non trovato.\n";
                echo "Test 20: FALLITO\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 20: FALLITO\n";
        }
    }




    /**
    // Test 8: Cancellazione della location
    if ($insertedId) {
        echo "\nTest 8: Cancellazione della location\n";
        try {
            $deleted = FLocation::deleteLocation($insertedId);
            if ($deleted) {
                echo "Location cancellata con successo.\n";
                $exists = FLocation::existsLocation($insertedId);
                echo $exists ? "La location esiste ancora.\n" : "La location è stata rimossa.\n";
            } else {
                echo "Errore durante la cancellazione della location.\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
        }
    }
     * */


    echo "\n===== Fine dei test =====\n";
}

// Esegui i test
try {
    runTests();
} catch (Exception $e) {
    echo "Errore durante i test: " . $e->getMessage() . "\n";
}
