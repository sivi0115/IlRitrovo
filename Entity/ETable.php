<?php

namespace Entity;
require_once 'EArea.php';
use InvalidArgumentException;
use JsonSerializable;


/**
 * Class that rapresents tables on an Area.
 */
class ETable extends EArea implements JsonSerializable {

    /**
     * @var bool Show a table statement, (true:avalible, false=not avalible)
     */
    private bool $statement;

    /**
     * Table Constructor
     * 
     * @param int table's number
     * @param string table's name (non credo serva) se c'è giù l'ID
     * @param int table's max guests
     */
    public function __construct(?int $idTable, string $tableName, int $maxGuests, bool $statement) {
        parent::__construct($idTable, $tableName, $maxGuests);
        $this->statement=$statement;
    }

    /**
     * Gest the table's statement
     * 
     * @return bool return the statement of the table
     */
    public function getTableState(): bool {
        return $this->statement;
    }

    /**
     * Sets the table's statement
     * 
     * @param $statement 
     */
    public function setTableState($statement): void {
        $this->statement=$statement;
    }

    /**
     * Metodo per serializzare l'oggetto in JSON.
     *
     * @return array Rappresentazione dell'oggetto come array associativo.
     */
    public function jsonSerialize(): array
    {
        return [
            'idTable' => $this->getIdArea(),
            'name' => $this->getareaName(),
            'maxGuests' => $this->getMaxGuests(),
            'state'=> $this->statement,
        ];
    }
}