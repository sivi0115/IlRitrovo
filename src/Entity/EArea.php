<?php

namespace Entity;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class EArea
 * Represents the user with their main properties.
 */
class EArea implements JsonSerializable {
    /**
     * METADATA
     * @var string area's name
     */
    private string $areaName;

    /**
     * @var int max guests in a specific area
     */
    private int $maxGuests;

    /**
     * Constructor for the EUser class with validation checks.
     *
     * @param string $name area's name
     * @param int $maxGuests max number of guests (can't be negative).
     * @throws InvalidArgumentException if one of the values not corretct
     */
    public function __construct(string $areaName, int $maxGuests) {
        if (empty($areaName)) {
            throw new InvalidArgumentException("Il nome dell'area non può essere vuoto.");
        }
        if ($maxGuests < 0) {
            throw new InvalidArgumentException("Il numero massimo di ospiti dell'area non può essere negativo.");
        }
        $this->areaName = $areaName;
        $this->maxGuests = $maxGuests;
    }

    /**
     * Gets area's name.
     *
     * @return string area's name.
     */
    public function getareaName(): string {
        return $this->areaName;
    }

    /**
     * Sets area's name.
     *
     * @param string $name area's name.
     * @throws InvalidArgumentException Se il nome è vuoto.
     */
    public function setName(string $name): void {
        if (empty($name)) {
            throw new InvalidArgumentException("Il nome dell'area non può essere vuoto.");
        }
        $this->areaName = $name;
    }

    /**
     * Gets max number of guests
     *
     * @return int max guests
     */
    public function getMaxGuests(): int {
        return $this->maxGuests;
    }

    /**
     * Sets max number of guests.
     *
     * @param int $maxGuests max guests.
     * @throws InvalidArgumentException if a negative number.
     */
    public function setMaxGuests(int $maxGuests): void {
        if ($maxGuests < 0) {
            throw new InvalidArgumentException("Il numero massimo di ospiti di un'area non può essere negativo.");
        }
        $this->maxGuests = $maxGuests;
    }

    /**
     * Implementation of the jsonSerialize method.
     *
     * @return array Associative array of the object's properties..
     */
    public function jsonSerialize(): array {
        return [
            'name' => $this->areaName,
            'maxGuests' => $this->maxGuests,
        ];
    }
}
