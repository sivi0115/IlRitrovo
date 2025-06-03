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
        2,
        null,
        'usernameAdminDiProva',
        'admin.prova@gmail,com',
        'password123@1!',
        'immagine.jpeg',
        'marco',
        'cipriani',
        new DateTime('2000-02-20'),
        '+393408993462',
        Role::AMMINISTRATORE,
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
            echo "Utente caricato correttamente: " . json_encode($user, JSON_PRETTY_PRINT) . "\n";
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
 * Funzione per modificare i meta dati profilo di un Utente nel db
 */
function testUpdateMetadataProfile(): void {
     $existingId=1; //ID DELL'OGGETTO DA MODIFICARE
    echo "\nTest 3: Aggiornamento di un utente\n";
    try {
        $fUser = new FUser(FDatabase::getInstance());
        $user = $fUser->read($existingId);
        
        if (!$user) {
            echo "ERRORE: Utente con ID $existingId non trovato";
            return;
        }
        //MODIFICA I DATI DELL'OGGETTO
        $user->setUsername('marcolino00');
        $user->setEmail('marcociprianituna2000@gmail.com');
        $result=$fUser->updateMetadataProfile($user);

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
        $allUsers = $fUser->readAll();

        echo "Totale utenti caricati: " . count($allUsers) . "\n";
        echo "Dettagli: " . json_encode($allUsers) . "\n";
        echo "Test 6: PASSATO\n";
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
        echo "Test 6: FALLITO\n";
    }
}

/**
 * Funzione per bannare un utente
 */
function testBanUser(): void {
    echo "\n Test per bannare un utente\n";

    $fUser=new FUser(FDatabase::getInstance());
    $admin=$fUser->read(2);
    $target=$fUser->read(1);
    $blocked=$fUser->banUser($admin, $target);

    if ($blocked === true) {
        echo "Operazione avvenuta con successo\n";
        echo "Utente con ID: " . $target->getIdUser() . " bannato con successo";
    } else {
        echo "Operazione fallita";
    }
}

/**
 * Funzione per sbannare un utente
 */
function testSbanUser(): void {
    echo "\n Test per sbannare un utente";

    $fUser=new FUser(FDatabase::getInstance());
    $admin=$fUser->read(2);
    $target=$fUser->read(1);
    $blocked=$fUser->unbanUser($admin, $target);

    if ($blocked === true) {
        echo "Operazione avvenuta con successo\n";
        echo "Utente con ID: " . $target->getIdUser() . " sbannato correttamente";
    } else {
        echo "Operazione fallita"; 
    }
}

/**
 * Funzione per caricare tutti gli utenti bannati da db
 */
function testReadAllBlockedUser(): void {
    echo "\n Test di caricamento di tutti gli utenti bloccati\n";

    $fUser=new FUser(FDatabase::getInstance());
    $banUsers=$fUser->readBlockedUsers();

    foreach($banUsers as $user) {
        if(!$user instanceof EUser) {
            echo "Caricamento fallito";
        } else {
            echo "Operazione avvenuta con successo\n";
            echo "Ecco tutti gli utenti bannati: \n";
            echo json_encode($banUsers, JSON_PRETTY_PRINT);
        }
    }
}














//testCreateUser();
//testReadUser();
testUpdateMetadataProfile();
//testReadAllUsers();
//testBanUser();
//testSbanUser();
//testReadAllBlockedUser();



