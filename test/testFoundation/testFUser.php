<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Foundation\FUser;
use Entity\EUser;
use Entity\Role;
use Foundation\FDatabase;

/**
 * Funzione per ottenere i dati di un test user.
 * @throws Exception
 */
function getTestUserData(): EUser
{
    return new EUser(
        3,
        null,
        'usernameDiProva',
        'provadueee@gmail,com',
        'password123@1!',
        'immagine.jpeg',
        'marco',
        'cipriani',
        new DateTime('2000-02-20'),
        '+393408993462',
        Role::UTENTE,
        false
    );
}

/**
 * Funzione per creare un nuovo utente nel db
 */
function testCreateUser(): void {
    echo "\nTest 1: Inserimento di un nuovo utente\n";
    try {
        $fUser = new FUser(FDatabase::getInstance());
        $testUser = getTestUserData();
        $insertedId = $fUser->create($testUser);

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
 * Carica un utente dal db
 */
function testReadUser(): void {
    $idKnown=2; //ID DA CARICARE
    echo "\nTest 2: Caricamento Utente tramite il suo ID\n";
    try {
        $fUser = new FUser(FDatabase::getInstance());
        $user = $fUser->read($idKnown);

        if ($user instanceof EUser) {
            echo "Utente caricato correttamente: " . json_encode($user) . "\n";
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
 * Funzione per modificare i dati di un Utente nel db
 */
function testUpdateUser(): void {
     $existingId=2; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di un utente\n";
    try {
        $fUser = new FUser(FDatabase::getInstance());
        $user = $fUser->read($existingId);
        
        if (!$user) {
            echo "ERRORE: Utente con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $user->setEmail('emailnuovadue@hotmal.it');
        $user->setBirthDate(new DateTime('2001-01-04'));
        $user->setPassword('nuovaPasswooooord123!');
        $user->setUsername('saretta01');
        $user->setRole('admin');
        $result=$fUser->update($user);

        if ($result) {
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
 * Funzione che carica tutti gli utenti nel db
 */
function testReadAllUsers(): void
{
    echo "\nTest 6: Caricamento di tutti gli utenti\n";
    try {
        $fUser = new FUser(FDatabase::getInstance());
        $allUsers = $fUser->readAllUsers();

        echo "Totale utenti caricati: " . count($allUsers) . "\n";
        echo "Dettagli: " . json_encode($allUsers) . "\n";
        echo "Test 6: PASSATO\n";
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 6: FALLITO\n";
    }
}

















//testCreateUser();
//testReadUser();
//testUpdateUser();
//testReadAllUsers();



