<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FUser;
use Entity\EUser;

/**
 * Funzione per ottenere i dati di un test user.
 * @throws Exception
 */
function getTestUserData(): EUser
{
    return new EUser(
        null, // ID generato automaticamente, quindi null
        'testuser',
        'Test',
        'User',
        new DateTime('2000-01-01'),
        '1234567890',
        'https://example.com/image.jpg',
        'testuser@example.com',
        'securePassword@123', // Password
        0, // Non bannato
        null // Motivazione
    );
}

$insertedId = null; // Variabile per memorizzare l'ID inserito

/**
 * Testa l'inserimento di un utente.
 */
function testCreateUser(): void
{
    global $insertedId;
    echo "\nTest 1: Inserimento di un nuovo utente\n";
    try {
        $fUser = new FUser();
        $testUser = getTestUserData();
        $insertedId = $fUser->createUser($testUser);

        if ($insertedId !== 0) {
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
 * Testa la lettura di un utente per ID.
 */
function testReadUserById(): void
{
    global $insertedId;
    echo "\nTest 2: Lettura di un utente tramite ID\n";
    if ($insertedId === null) {
        echo "Test 2: Nessun ID disponibile. Esegui prima il test di inserimento.\n";
        return;
    }
    try {
        $fUser = new FUser();
        $user = $fUser->readUser($insertedId);

        if ($user instanceof EUser) {
            echo "Utente trovato: " . json_encode($user) . "\n";
            echo "Test 2: PASSATO\n";
        } else {
            echo "Utente non trovato.\n";
            echo "Test 2: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 2: FALLITO\n";
    }
}

/**
 * Testa l'aggiornamento di un utente.
 */
function testUpdateUser(): void
{
    global $insertedId;
    echo "\nTest 3: Aggiornamento di un utente\n";
    if ($insertedId === null) {
        echo "Test 3: Nessun ID disponibile. Esegui prima il test di inserimento.\n";
        return;
    }
    try {
        $fUser = new FUser();
        $updatedUser = getTestUserData();
        $updatedUser->setUsername('updateduser');
        $updatedUser->setIdUser($insertedId);

        $updated = $fUser->updateUser($updatedUser);

        if ($updated) {
            echo "Utente aggiornato correttamente.\n";
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
 * Testa la lettura di un utente per username.
 */
function testReadUserByUsername(): void
{
    global $insertedId;
    echo "\nTest 4: Lettura di un utente tramite username\n";
    if ($insertedId === null) {
        echo "Test 4: Nessun ID disponibile. Esegui prima il test di inserimento.\n";
        return;
    }
    try {
        $fUser = new FUser();
        $user = $fUser->readUserByUsername('updateduser');

        if ($user instanceof EUser) {
            echo "Utente trovato: " . json_encode($user) . "\n";
            echo "Test 4: PASSATO\n";
        } else {
            echo "Utente non trovato.\n";
            echo "Test 4: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 4: FALLITO\n";
    }
}

/**
 * Testa la lettura di tutti gli utenti.
 */
function testReadAllUsers(): void
{
    echo "\nTest 5: Lettura di tutti gli utenti\n";
    try {
        $fUser = new FUser();
        $users = $fUser->readAllUsers();

        if (!empty($users)) {
            echo "Utenti trovati: " . json_encode($users) . "\n";
            echo "Test 5: PASSATO\n";
        } else {
            echo "Nessun utente trovato.\n";
            echo "Test 5: PASSATO (se il database è vuoto)\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 5: FALLITO\n";
    }
}

/**
 * Testa la cancellazione di un utente.
 */
function testDeleteUser(): void
{
    global $insertedId;
    echo "\nTest 6: Cancellazione di un utente\n";
    if ($insertedId === null) {
        echo "Test 6: Nessun ID disponibile. Esegui prima il test di inserimento.\n";
        return;
    }
    try {
        $fUser = new FUser();
        $deleted = $fUser->deleteUser($insertedId);

        if ($deleted) {
            echo "Utente cancellato correttamente.\n";
            echo "Test 6: PASSATO\n";
        } else {
            echo "Cancellazione fallita.\n";
            echo "Test 6: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 6: FALLITO\n";
    }
}

/**
 * Testa il ban di un utente.
 */
function testBanUser(): void
{
    global $insertedId;
    echo "\nTest 7: Ban di un utente\n";
    if ($insertedId === null) {
        echo "Test 7: Nessun ID disponibile. Esegui prima il test di inserimento.\n";
        return;
    }
    try {
        $fUser = new FUser();
        $banned = $fUser->banUser($insertedId, 'Test ban motivation');

        if ($banned) {
            echo "Utente bannato correttamente.\n";
            echo "Test 7: PASSATO\n";
        } else {
            echo "Ban fallito.\n";
            echo "Test 7: FALLITO\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 7: FALLITO\n";
    }

    /**
     * Testa l'esistenza di un utente per campo specifico.
     */
    function testExistsUser(): void
    {
        global $insertedId;
        echo "\nTest 8: Verifica esistenza di un utente\n";
        if ($insertedId === null) {
            echo "Test 8: Nessun ID disponibile. Esegui prima il test di inserimento.\n";
            return;
        }
        try {
            $fUser = new FUser();
            $exists = $fUser->existsUser('idUser', $insertedId);

            if ($exists) {
                echo "L'utente esiste nel database.\n";
                echo "Test 8: PASSATO\n";
            } else {
                echo "L'utente non esiste nel database.\n";
                echo "Test 8: FALLITO\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 8: FALLITO\n";
        }
    }


    /**
     * Testa la restituzione degli utenti bloccati.
     */
    function testGetBlockedUsers(): void
    {
        global $insertedId;
        echo "\nTest 9: Recupero degli utenti bloccati\n";
        if ($insertedId === null) {
            echo "Test 9: Nessun ID disponibile. Esegui prima il test di inserimento.\n";
            return;
        }
        try {
            $fUser = new FUser();
            $fUser->banUser($insertedId, 'Motivazione di test');
            $blockedUsers = $fUser->getBlockedUsers();

            if (!empty($blockedUsers)) {
                echo "Utenti bloccati trovati: " . json_encode($blockedUsers) . "\n";
                echo "Test 9: PASSATO\n";
            } else {
                echo "Nessun utente bloccato trovato.\n";
                echo "Test 9: FALLITO\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 9: FALLITO\n";
        }
    }

    /**
     * Testa l'inserimento di un utente con dati non validi.
     */
    function testCreateUserWithInvalidData(): void
    {
        echo "\nTest 10: Inserimento di un utente con dati non validi\n";
        try {
            $fUser = new FUser();
            $invalidUser = new EUser(
                null,
                '', // Username vuoto
                '', // Nome vuoto
                '', // Cognome vuoto
                new DateTime('1900-01-01'), // Data di nascita fittizia
                '', // Telefono vuoto
                '', // Immagine vuota
                '', // Email vuota
                '', // Password vuota
                0,
                null
            );

            $insertedId = $fUser->createUser($invalidUser);

            if ($insertedId === 0) {
                echo "Inserimento fallito come previsto.\n";
                echo "Test 10: PASSATO\n";
            } else {
                echo "Inserimento riuscito inaspettatamente. ID: $insertedId\n";
                echo "Test 10: FALLITO\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 10: PASSATO (se il sistema gestisce correttamente l'errore)\n";
        }
    }

    /**
     * Testa la lettura di un utente con ID non valido.
     */
    function testReadUserInvalidId(): void
    {
        echo "\nTest 11: Lettura di un utente con ID non valido\n";
        try {
            $fUser = new FUser();
            $user = $fUser->readUser(-1); // ID non valido

            if ($user === null) {
                echo "Utente non trovato come previsto.\n";
                echo "Test 11: PASSATO\n";
            } else {
                echo "Utente trovato inaspettatamente: " . json_encode($user) . "\n";
                echo "Test 11: FALLITO\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 11: PASSATO (se il sistema gestisce correttamente l'errore)\n";
        }
    }

    /**
     * Testa la lettura di un utente con uno username non valido.
     */
    function testReadUserInvalidUsername(): void
    {
        echo "\nTest 12: Lettura di un utente con uno username non valido\n";
        try {
            $fUser = new FUser();
            $user = $fUser->readUserByUsername('nonexistentusername'); // Username non valido

            if ($user === null) {
                echo "Utente non trovato come previsto.\n";
                echo "Test 12: PASSATO\n";
            } else {
                echo "Utente trovato inaspettatamente: " . json_encode($user) . "\n";
                echo "Test 12: FALLITO\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 12: PASSATO (se il sistema gestisce correttamente l'errore)\n";
        }
    }

    /**
     * Testa la cancellazione di un utente già cancellato.
     */
    function testDeleteUserAlreadyDeleted(): void
    {
        global $insertedId;
        echo "\nTest 13: Cancellazione di un utente già cancellato\n";
        if ($insertedId === null) {
            echo "Test 13: Nessun ID disponibile. Esegui prima il test di inserimento.\n";
            return;
        }
        try {
            $fUser = new FUser();
            $fUser->deleteUser($insertedId); // Cancella l'utente
            $deletedAgain = $fUser->deleteUser($insertedId); // Riprova a cancellarlo

            if (!$deletedAgain) {
                echo "Seconda cancellazione fallita come previsto.\n";
                echo "Test 13: PASSATO\n";
            } else {
                echo "Seconda cancellazione riuscita inaspettatamente.\n";
                echo "Test 13: FALLITO\n";
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage() . "\n";
            echo "Test 13: PASSATO (se il sistema gestisce correttamente l'errore)\n";
        }
    }

}



// Esecuzione dei test
echo "Esecuzione dei test...\n";

testCreateUser();
testReadUserById();
testUpdateUser();
testReadUserByUsername();
testReadAllUsers();
testBanUser();
testExistsUser ();
testGetBlockedUsers ();
testCreateUserWithInvalidData ();
testReadUserInvalidId ();
testReadUserInvalidUsername ();
testDeleteUser();
testDeleteUserAlreadyDeleted ();


