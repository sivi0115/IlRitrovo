<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use Entity\EUser;
use Entity\Role;
use Foundation\FDatabase;
use Foundation\FPersistentManager;
use Foundation\FUser;


//Istanza di un nuovo oggetto che verrà utilizzato da PM
$e1=new EUser(
    2,
    null,
    'usernameProva',
    'emailProva@gmail.com',
    'passwordNuova123!',
    'immagineProva.jpg',
    'nomeProva',
    'cognomeProva',
    new DateTime('2000-01-01'),
    '3408993461',
    Role::UTENTE,
    false
);

//Istanza di PM
$pm=FPersistentManager::getInstance();

/**
 * Funzione di create
 */
function testCreatePm(): void {
    global $pm, $e1;
    echo "\n Testing per creare un oggetto su db con PM\n";

    try {
        $pm->create($e1);
        echo "Utente creato con successo";
    } catch (Exception $e) {
        echo "Errore durante la creazione" . $e->getMessage() . "\n";
    }
}

/**
 * Funzione di reading dal db tramite PM
 */
function testReadPm(): void {
    global $pm;
    echo "\n Testing per caricare un oggetto dal db\n";

    try {
        $utente=$pm->read(2, FUser::class);

        if($utente instanceof EUser) {
            echo "Test passato con successo";
            echo "\n Oggetto letto dal DB: " . json_encode($utente, JSON_PRETTY_PRINT);
        } else {
            echo "Test Fallito";
        }
    } catch (Exception $e) {
        echo "Errore durante il caricamento" . $e->getMessage();
    }
}

/**
 * Funzione per modificare i dati di un utente
 */
function testUpdatePm(): void {
    global $pm;

    echo "\n Testing per aggiornare un oggetto nel db\n";

    try {
        // Carico l'oggetto dal DB
        $utente = $pm->read(2, FUser::class);

        // Modifico un attributo
        $utente->setUsername('usernameAggiornato');
        $utente->setEmail('emailaggionrata@libero.com');
        $utente->setName('nuovoNomeDiAggionramento');

        // Eseguo l'update
        $pm->update($utente);

        // Rileggo per conferma
        $utenteAggiornato = $pm->read(2, FUser::class);

        if ($utenteAggiornato->getName() === 'NomeAggiornato') {
            echo "Test di aggiornamento passato con successo!\n";
        } else {
            echo "Test fallito: nome non aggiornato.\n";
        }
    } catch (Exception $e) {
        echo "Errore durante l'update: " . $e->getMessage() . "\n";
    }
}

/**
 * Funzione per eliminare un oggetto da db
 */
function testDelete(): void {
    global $pm;
    $idKnowed=2;
    $deleted=$pm->delete($idKnowed, FUser::class);
    echo "Oggetto eliminato con successo";
}

function testReadAll(): void {
    global $pm;
    echo "\nTesting per caricare tutti gli oggetti da db\n";

    try {
        $result = $pm->readAll(FUser::class);

        // Verifica che tutti gli oggetti siano di tipo EUser
        foreach ($result as $row) {
            if (!($row instanceof EUser)) {
                echo "Test Fallito: trovato un oggetto non EUser\n";
                return;
            }
        }

        echo "Test superato: tutti gli oggetti sono istanze di EUser\n";
        echo "Tutti gli utenti nel db:\n";
        echo json_encode($result, JSON_PRETTY_PRINT);

    } catch (Exception $e) {
        echo "Errore durante il caricamento: " . $e->getMessage();
    }
}

/**
 * Funzione per verificare che un oggetto esista nel db
 */
function testExists(): void {
    global $pm;
    $knowedId=3;
    echo "\n Test per verificare l'esistenza di un oggetto\n";

    try {
        $result=$pm->exists($knowedId, FUser::class);
        
        if($result === true) {
            echo "Test superato\n";
            echo "Ecco l'oggetto presente nel db\n";
            $obj=$pm->read($knowedId, FUser::class);
            echo json_encode($obj, JSON_PRETTY_PRINT);
        } else {
            echo "Test fallito\n";
            echo "L'id $knowedId non esiste nel db";
        }
    } catch (Exception $e) {
        echo "Errore durante il caricamento" . $e->getMessage();
    }
}





//testCreatePm();
//testReadPm();
//testUpdatePm();
//testDelete();
//testReadAll();
//testExists();