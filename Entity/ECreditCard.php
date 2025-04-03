<?php

namespace Entity;

use DateTime;
use Exception;
use JsonSerializable;

/**
 * Class ECreditCard
 *
 * Represents a credit card entity with relevant details.
 */
class ECreditCard implements JsonSerializable
{
    /**
     * @var int|null The unique identifier for the credit card, managed by the database.
     */
    private ?int $idCreditCard;

    /**
     * @var string The credit card number.
     */
    private string $number;

    /**
     * @var int The CVV (Card Verification Value) of the credit card.
     */
    private int $cvv;

    /**
     * @var DateTime The expiration date of the credit card.
     */
    private DateTime $expiration;

    /**
     * @var string The name of the cardholder.
     */
    private string $holder;

    /**
     * @var string The type of the credit card (e.g., Visa, MasterCard).
     */
    private string $type;

    /**
     * @var int|null The ID of the user associated with the credit card (optional).
     */
    private ?int $idUser;

    /**
     * ECreditCard constructor.
     *
     * @param int|null $idCreditCard The unique identifier for the credit card (optional).
     * @param string $number The credit card number.
     * @param int $cvv The CVV of the credit card.
     * @param DateTime $expiration The expiration date of the credit card.
     * @param string $holder The cardholder's name.
     * @param string $type The type of the credit card (e.g., Visa, MasterCard).
     * @param int|null $idUser The ID of the user associated with the credit card (optional).
     *
     * @throws Exception If the credit card number, CVV, expiration date, holder name, or type is invalid.
     */
    public function __construct(
        ?int $idCreditCard,
        string $number,
        int $cvv,
        DateTime $expiration,
        string $holder,
        string $type,
        ?int $idUser = null
    ) {
        $this->setIdCreditCard($idCreditCard);
        $this->setNumber($number);
        $this->setCvv($cvv);
        $this->setExpiration($expiration);
        $this->setHolder($holder);
        $this->setType($type);
        $this->idUser = $idUser;
    }

    /**
     * Gets the unique identifier for the credit card.
     *
     * @return int|null The ID of the credit card or null if not set.
     */
    public function getIdCreditCard(): ?int
    {
        return $this->idCreditCard;
    }

    /**
     * Sets the unique identifier for the credit card.
     *
     * @param int|null $idCreditCard The ID to set.
     */
    public function setIdCreditCard(?int $idCreditCard): void
    {
        $this->idCreditCard = $idCreditCard;
    }

    /**
     * Gets the credit card number.
     *
     * @return string The credit card number.
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Sets the credit card number.
     *
     * @param string $number The credit card number to set.
     * @throws Exception If the card number is invalid.
     */
    public function setNumber(string $number): void
    {
        if (!$this->isValidCardNumber($number)) {
            throw new Exception("Invalid credit card number. Must be between 13 and 19 digits.");
        }
        $this->number = $number;
    }

    /**
     * Gets the CVV of the credit card.
     *
     * @return int The CVV of the credit card.
     */
    public function getCvv(): int
    {
        return $this->cvv;
    }

    /**
     * Sets the CVV of the credit card.
     *
     * @param int $cvv The CVV to set.
     * @throws Exception If the CVV is invalid.
     */
    public function setCvv(int $cvv): void
    {
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            throw new Exception("Invalid CVV. Must be 3 or 4 digits.");
        }
        $this->cvv = $cvv;
    }

    /**
     * Gets the expiration date of the credit card.
     *
     * @return DateTime The expiration date.
     */
    public function getExpiration(): DateTime
    {
        return $this->expiration;
    }

    /**
     * Sets the expiration date of the credit card.
     *
     * @param DateTime $expiration The expiration date to set.
     * @throws Exception If the expiration date is in the past.
     */
    public function setExpiration(DateTime $expiration): void
    {
        if ($expiration < new DateTime()) {
            throw new Exception("Expiration date must be in the future.");
        }
        $this->expiration = $expiration;
    }

    /**
     * Gets the cardholder's name.
     *
     * @return string The cardholder's name.
     */
    public function getHolder(): string
    {
        return $this->holder;
    }

    /**
     * Sets the cardholder's name.
     *
     * @param string $holder The cardholder's name to set.
     * @throws Exception If the cardholder's name is empty.
     */
    public function setHolder(string $holder): void
    {
        if (empty($holder)) {
            throw new Exception("Cardholder name cannot be empty.");
        }
        $this->holder = $holder;
    }

    /**
     * Gets the type of the credit card (e.g., Visa, MasterCard).
     *
     * @return string The type of the credit card.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the credit card.
     *
     * @param string $type The type of the credit card (e.g., Visa, MasterCard).
     * @throws Exception If the card type is empty or invalid.
     */
    public function setType(string $type): void
    {
        $validTypes = ['Visa', 'Mastercard', 'American Express', 'Maestro', 'V-Pay', 'PagoBANCOMAT'];
        if (empty($type) || !in_array($type, $validTypes)) {
            throw new Exception("Invalid card type.");
        }
        $this->type = $type;
    }

    /**
     * Gets the ID of the user associated with the credit card.
     *
     * @return int|null The user ID or null if not set.
     */
    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    /**
     * Sets the ID of the user associated with the credit card.
     *
     * @param int|null $idUser The user ID to set.
     */
    public function setIdUser(?int $idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * Validates the credit card number.
     *
     * @param string $number The credit card number to validate.
     * @return bool True if valid, false otherwise.
     */
    private function isValidCardNumber(string $number): bool
    {
        return preg_match('/^\d{13,19}$/', $number);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array The data to be serialized.
     */
    public function jsonSerialize(): array
    {
        return [
            'idCreditCard' => $this->idCreditCard,
            'number' => $this->number,
            'cvv' => $this->cvv,
            'expiration' => $this->expiration->format('Y-m-d'),
            'holder' => $this->holder,
            'type' => $this->type,
            'idUser' => $this->idUser
        ];
    }
}
