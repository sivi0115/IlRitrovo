<?php

namespace Entity;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Classe che rappresenta un'area (EArea).
 * Implementa l'interfaccia JsonSerializable per la serializzazione JSON.
 */
class EArea implements JsonSerializable
{
    /** @var int|null ID dell'area, opzionale */
    private ?int $idArea;

    /** @var string Nome dell'area */
    private string $areaName;

    /** @var int Capacità massima dell'area */
    private int $maxGuests;

    /**
     * Costruttore della classe EArea.
     *
     * @param int|null $idArea ID dell'area (null se non specificato).
     * @param string $name Nome dell'area.
     * @param int $maxGuests Numero massimo di ospiti dell'area (non può essere negativa).
     * @throws InvalidArgumentException Se uno dei parametri non rispetta i vincoli.
     */
    public function __construct(?int $idArea, string $name, int $maxGuests)
    {
        if ($idArea !== null && $idArea < 0) {
            throw new InvalidArgumentException("L'ID dell'area non può essere negativo.");
        }
        if (empty($areaName)) {
            throw new InvalidArgumentException("Il nome dell'area non può essere vuoto.");
        }
        if ($maxGuests < 0) {
            throw new InvalidArgumentException("Il numero massimo di ospiti dell'area non può essere negativo.");
        }

        $this->idArea = $idArea;
        $this->areaName = $name;
        $this->maxGuests = $maxGuests;
    }

    /**
     * Ottiene l'ID dell'area.
     *
     * @return int|null ID dell'area  o null se non specificato.
     */
    public function getIdArea(): ?int
    {
        return $this->idArea;
    }

    /**
     * Ottiene il nome dell'area.
     *
     * @return string Nome dell'area.
     */
    public function getareaName(): string
    {
        return $this->areaName;
    }

    /**
     * Imposta il nome dell'area.
     *
     * @param string $name Nome dell'area.
     *
     * @throws InvalidArgumentException Se il nome è vuoto.
     */
    public function setName(string $name): void
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Il nome dell'area non può essere vuoto.");
        }
        $this->areaName = $name;
    }

    /**
     * Ottiene il numero massimo di ospiti per l'area
     *
     * @return int Numero massimo di ospiti per l'area
     */
    public function getMaxGuests(): int
    {
        return $this->maxGuests;
    }

    /**
     * Imposta il numero massimo di ospiti per l'area.
     *
     * @param int $maxGuests Numero massimo di ospiti dell'area.
     *
     * @throws InvalidArgumentException Se il numero è negativo.
     */
    public function setMaxGuests(int $maxGuests): void
    {
        if ($maxGuests < 0) {
            throw new InvalidArgumentException("Il numero massimo di ospiti di un'area non può essere negativo.");
        }
        $this->maxGuests = $maxGuests;
    }

    /**
     * Metodo per serializzare l'oggetto in JSON.
     *
     * @return array Rappresentazione dell'oggetto come array associativo.
     */
    public function jsonSerialize(): array
    {
        return [
            'idArea' => $this->idArea,
            'name' => $this->areaName,
            'maxGuests' => $this->maxGuests,
        ];
    }
}
