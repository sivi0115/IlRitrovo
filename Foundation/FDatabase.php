<?php

namespace Foundation;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

/**
 * Class FDatabase
 * Gestisce la connessione al database e le query.
 */
class FDatabase
{
    private static ?FDatabase $instance = null;
    private ?PDO $connection = null;

    /**
     * FDatabase constructor.
     * Costruttore privato per implementare il pattern Singleton.
     *
     * @throws Exception Se la connessione al database fallisce.
     */
    private function __construct()
    {
        try {
            // Include the config file
            require_once __DIR__ . '/../../config.php';

            // Create a new PDO instance
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USERNAME,
                DB_PASSWORD
            );

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Connessione al database fallita: " . $e->getMessage());
        }
    }

    /**
     * Restituisce l'istanza singleton di FDatabase.
     *
     * @return FDatabase L'istanza di FDatabase.
     */
    public static function getInstance(): FDatabase
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Chiude la connessione al database.
     */
    public function closeDbConnection(): void
    {
        $this->connection = null;
        self::$instance = null;
    }

    /**
     * Begin transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function beginTransaction(): bool
    {
        try {
            return $this->connection->beginTransaction();
        } catch (PDOException $e) {
            error_log("Errore durante l'avvio della transazione: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Commit transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function commit(): bool
    {
        try {
            return $this->connection->commit();
        } catch (PDOException $e) {
            error_log("Errore durante il commit della transazione: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rollback transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function rollback(): bool
    {
        try {
            return $this->connection->rollBack();
        } catch (PDOException $e) {
            error_log("Errore durante il rollback della transazione: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Metodo generico per eseguire query.
     *
     * @param string $sql La query SQL da eseguire.
     * @param array $params I parametri per il binding.
     * @return PDOStatement|null L'oggetto PDOStatement o null in caso di errore.
     */
    private function query(string $sql, array $params = []): ?PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($sql);


            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Errore nell'esecuzione della query: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Esegue una query SQL.
     *
     * @param string $sql La query SQL da eseguire.
     * @param array $params I parametri da passare alla query.
     * @return bool True se l'esecuzione ha successo, false altrimenti.
     */
    public function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params) !== null;
    }


    /**
     * Esegue una query SQL e recupera tutte le righe risultanti.
     *
     * @param string $query La query SQL da eseguire.
     * @param array $params I parametri da passare alla query.
     * @return array Un array di tutte le righe risultanti.
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function fetchAllFromTable(string $table, array $conditions = []): array
    {
        $whereClause = '';
        $params = [];

        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
            $params = array_values($conditions);
        }

        $query = "SELECT * FROM $table $whereClause";
        return $this->fetchAll($query, $params);
    }

    /**
     * Esegue una query SQL e recupera una singola riga risultante.
     *
     * @param string $query La query SQL da eseguire.
     * @param array $params I parametri da passare alla query.
     * @return mixed La riga risultante o false se non ci sono risultati.
     */
    public function fetchSingle(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->query($sql, $params);
            $result = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
            return $result;
        } catch (Exception $e) {
            error_log("Error in fetchSingle: " . $e->getMessage());
            return null;
        }
    }



    /**
     * Inserisce un nuovo record nel database e restituisce l'ID del record inserito.
     *
     * @param string $table Il nome della tabella.
     * @param array $data Un array associativo di dati da inserire.
     * @return int|null L'ID del record inserito o null in caso di errore.
     */
    public function insert(string $table, array $data): ?int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->query($query, array_values($data));
        return $stmt ? (int) $this->connection->lastInsertId() : null;
    }


    /**
     * Aggiorna un record nel database.
     *
     * @param string $table Il nome della tabella.
     * @param array $data Un array associativo di dati da aggiornare.
     * @param string $whereColumn La colonna da utilizzare nella clausola WHERE.
     * @param mixed $whereValue Il valore da utilizzare nella clausola WHERE.
     * @return bool True se l'aggiornamento ha successo, false altrimenti.
     */
    public function update(string $table, array $data, array $conditions): bool
    {
        $setClause = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $whereClause = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
        $query = "UPDATE $table SET $setClause WHERE $whereClause";
        return $this->execute($query, array_merge(array_values($data), array_values($conditions)));
    }


    /**
     * Prepares a SQL statement.
     *
     * @param string $sql The SQL statement to prepare.
     * @return PDOStatement The prepared statement.
     */
    public function prepare(string $sql): PDOStatement
    {
        return $this->connection->prepare($sql);
    }


    /**
     * Elimina un record dal database.
     *
     * @param string $table Il nome della tabella.
     * @param string $whereColumn La colonna da utilizzare nella clausola WHERE.
     * @param mixed $whereValue Il valore da utilizzare nella clausola WHERE.
     * @return bool True se l'eliminazione ha successo, false altrimenti.
     */
    public function delete(string $table, array $conditions): bool
    {
        $whereClause = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
        $query = "DELETE FROM $table WHERE $whereClause";
        return $this->execute($query, array_values($conditions));
    }

    /**
     * Carica un record dal database.
     *
     * @param string $table Il nome della tabella.
     * @param string $whereColumn La colonna da utilizzare nella clausola WHERE.
     * @param mixed $whereValue Il valore da utilizzare nella clausola WHERE.
     * @return array|null Un array associativo che rappresenta il record o null se non trovato.
     */
    public function load(string $table, string $whereColumn, mixed $whereValue): ?array
    {
        $query = "SELECT * FROM $table WHERE $whereColumn = :value LIMIT 1";
        $stmt = $this->query($query, ['value' => $whereValue]);

        if ($stmt) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Ensure we return null if no result is found
            return $result ?: null;
        }

        return null;
    }

    public function loadMultiples(string $table, array $conditions = []): array
    {
        $whereClause = '';
        $params = [];
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($conditions)));
            $params = $conditions;
        }

        $query = "SELECT * FROM $table $whereClause";
        $stmt = $this->query($query, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function fetchWhere(string $table, array $conditions): array
    {
        if (empty($conditions)) {
            throw new Exception("Le condizioni non possono essere vuote per fetchWhere.");
        }

        $whereClause = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
        $query = "SELECT * FROM $table WHERE $whereClause";
        return $this->fetchAll($query, array_values($conditions));
    }


    public function fetchBetween(string $table, string $column, mixed $minValue, mixed $maxValue): array
    {
        $query = "SELECT * FROM $table WHERE $column BETWEEN ? AND ?";
        return $this->fetchAll($query, [$minValue, $maxValue]);
    }

    public function fetchLike(string $table, string $column, string $pattern): array
    {
        $query = "SELECT * FROM $table WHERE $column LIKE ?";
        return $this->fetchAll($query, [$pattern]);
    }

    /**
     * Controlla se un record esiste nel database.
     *
     * @param string $table
     * @param array $conditions
     * @return bool True se il record esiste, false altrimenti.
     */
    public function exists(string $table, array $conditions): bool
    {
        $whereClause = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
        $query = "SELECT 1 FROM $table WHERE $whereClause LIMIT 1";
        $stmt = $this->query($query, array_values($conditions));
        return $stmt ? $stmt->fetch() !== false : false;
    }


    /**
     * Get the last inserted ID.
     *
     * @return string|null The ID of the last inserted row or null on failure.
     */
    public function getLastInsertedId(): ?string
    {
        try {
            $lastId = $this->connection->lastInsertId();
            if (!$lastId) {
                throw new Exception("Nessun ID inserito disponibile.");
            }
            return $lastId;
        } catch (PDOException $e) {
            error_log("Errore durante il recupero dell'ultimo ID inserito: " . $e->getMessage());
            return null;
        }
    }

}