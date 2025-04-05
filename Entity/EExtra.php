<?php

namespace Entity;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class EExtra
 * Represents an extra service offered at the location.
 */
class EExtra implements JsonSerializable {
    /**
     * @var int|null The unique identifier for the extra service (managed by the database).
     */
    private ?int $idExtra;

    /**
     * @var string The name of the extra service.
     */
    private string $nameExtra;

    /**
     * @var int The price of the extra service (in cents).
     */
    private int $priceExtra;

    /**
     * Constructor for the EUser class with validation checks.
     *
     * @param int|null $idExtra The unique identifier for the extra service (managed by the database).
     * @param string $nameExtra The name of the extra service.
     * @param int $priceExtra The price of the extra service (in cents).
     * @throws InvalidArgumentException If the price is negative, or if the name is empty.
     */
    public function __construct(?int $idExtra, string $name, int $price) {
        if ($price < 0) {
            throw new InvalidArgumentException("Il prezzo non può essere negativo.");
        }
        if (empty($name)) {
            throw new InvalidArgumentException("Il nome del servizio extra non può essere vuoto.");
        }

        $this->idExtra = $idExtra;
        $this->nameExtra = $name;
        $this->priceExtra = $price;
    }

    /**
     * Get the unique identifier for the extra service.
     *
     * @return int|null The unique identifier, or null if not set.
     */
    public function getIdExtra(): ?int {
        return $this->idExtra;
    }

    /**
     * Set the unique identifier for the extra service.
     *
     * @param int|null $idExtra The ID to set.
     */
    public function setIdExtra(?int $idExtra): void {
        $this->idExtra = $idExtra;
    }

    /**
     * Get the name of the extra service.
     *
     * @return string The name of the extra service.
     */
    public function getNameExtra(): string {
        return $this->nameExtra;
    }

    /**
     * Set the name of the extra service.
     *
     * @param string $name The name to set.
     * @throws InvalidArgumentException If the name is empty.
     */
    public function setNameExtra(string $name): void {
        if (empty($name)) {
            throw new InvalidArgumentException("Il nome del servizio extra non può essere vuoto.");
        }
        $this->nameExtra = $name;
    }

    /**
     * Get the price of the extra service (in cents).
     *
     * @return int The price of the extra service in cents.
     */
    public function getPriceExtra(): int {
        return $this->priceExtra;
    }

    /**
     * Set the price of the extra service (in cents).
     *
     * @param int $price The price to set.
     * @throws InvalidArgumentException If the price is negative.
     */
    public function setPriceExtra(int $price): void {
        if ($price < 0) {
            throw new InvalidArgumentException("Il prezzo non può essere negativo.");
        }
        $this->priceExtra = $price;
    }

    /**
     * Implementation of the jsonSerialize method.
     *
     * @return array Associative array of the object's properties.
     */
    public function jsonSerialize(): array
    {
        return [
            'idExtra' => $this->idExtra,
            'name' => $this->nameExtra,
            'price' => $this->priceExtra / 100,
        ];
    }
}