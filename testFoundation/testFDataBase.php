<?php

require_once __DIR__ . '/../../vendor/autoload.php';

// Tester per FDatabase
function runFDatabaseTests()
{
    echo "Testing FDatabase\n";

    try {
        $db = Foundation\FDatabase::getInstance();

        // Creazione tabella di test
        $createTableQuery = "CREATE TABLE IF NOT EXISTS test_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            age INT NOT NULL
        )";
        $db->execute($createTableQuery);
        echo "- Table created successfully\n";

        // Inserimento di un record
        $insertData = ['name' => 'John Doe', 'age' => 30];
        $insertId = $db->insert('test_table', $insertData);
        echo "- Record inserted with ID: $insertId\n";

        // Verifica del record inserito
        $record = $db->load('test_table', 'id', $insertId);
        echo "- Record loaded: ";
        print_r($record);

        // Aggiornamento del record
        $updateData = ['name' => 'Jane Doe', 'age' => 35];
        $conditions = ['id' => $insertId];
        $db->update('test_table', $updateData, $conditions);
        echo "- Record updated\n";

        // Verifica aggiornamento
        $updatedRecord = $db->load('test_table', 'id', $insertId);
        echo "- Updated record: ";
        print_r($updatedRecord);

        // Caricamento multiplo
        $db->insert('test_table', ['name' => 'Alice', 'age' => 28]);
        $db->insert('test_table', ['name' => 'Bob', 'age' => 40]);
        $allRecords = $db->loadMultiples('test_table');
        echo "- All records: ";
        print_r($allRecords);

        // Controllo esistenza
        $exists = $db->exists('test_table', ['name' => 'Alice']);
        echo "- Record with name 'Alice' exists: ".($exists ? 'Yes' : 'No')."\n";

        // Fetch tra intervalli
        $betweenRecords = $db->fetchBetween('test_table', 'age', 30, 40);
        echo "- Records with age between 30 and 40: ";
        print_r($betweenRecords);

        // Fetch like
        $likeRecords = $db->fetchLike('test_table', 'name', '%Doe%');
        echo "- Records with name like '%Doe%': ";
        print_r($likeRecords);

        // Eliminazione di un record
        $db->delete('test_table', ['id' => $insertId]);
        echo "- Record with ID $insertId deleted\n";

        // Verifica eliminazione
        $deletedRecord = $db->load('test_table', 'id', $insertId);
        echo "- Deleted record loaded: ".($deletedRecord === null ? 'Not found' : 'Found')."\n";

        /**
        // Pulizia: elimina tutti i record
        $db->execute("DELETE FROM test_table");
        echo "- All records deleted\n";

        // Drop tabella
        $db->execute("DROP TABLE IF EXISTS test_table");
        echo "- Table dropped successfully\n";
         * */
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Esegui i test
runFDatabaseTests();
