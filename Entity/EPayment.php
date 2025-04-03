<?php

namespace Entity;

use DateTime;
use InvalidArgumentException;
use JsonSerializable;

enum StatoPagamento: string {
    case COMPLETATO = 'completed';
    case IN_ATTESA = 'pending';
    case FALLITO = 'failed';
    case ANNULLATO = 'canceled';
}

/**
 * Class EPayment
 *
 * This class represents a payment made for a reservation.
 * It includes the total amount, payment method, creation time, payment state, and the related credit card.
 */
class EPayment implements JsonSerializable
{
    private ?int $idPayment;
    private float $total;
    private DateTime $creationTime;
    private StatoPagamento $state;
    private int $idCreditCard;
    private int $idReservation;

    /**
     * EPayment constructor.
     *
     * @param int|null $idPayment The ID of the payment, or null if not yet created.
     * @param float $total The total amount to be paid.
     * @param StatoPagamento $state The state of the payment (e.g., completed, pending).
     * @param DateTime $creationTime The creation time of the payment.
     * @param int $idCreditCard The ID of the associated credit card.
     * @param int $idReservation The ID of the associated reservation.
     *
     * @throws InvalidArgumentException If the total amount is negative.
     */
    public function __construct(
        ?int $idPayment,
        float $total,
        StatoPagamento $state,
        DateTime $creationTime,
        int $idCreditCard,
        int $idReservation
    ) {
        if ($total < 0) {
            throw new InvalidArgumentException('Total amount must be non-negative.');
        }

        $this->idPayment = $idPayment;
        $this->total = $total;
        $this->state = $state;
        $this->creationTime = $creationTime;
        $this->idCreditCard = $idCreditCard;
        $this->idReservation = $idReservation;
    }

    /**
     * Get the ID of the payment.
     *
     * @return int|null The ID of the payment, or null if not set.
     */
    public function getIdPayment(): ?int
    {
        return $this->idPayment;
    }

    /**
     * Set the ID of the payment.
     *
     * @param int|null $idPayment The ID of the payment, or null to unset.
     */
    public function setIdPayment(?int $idPayment): void
    {
        $this->idPayment = $idPayment;
    }

    /**
     * Get the total amount to be paid.
     *
     * @return float The total amount.
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * Set the total amount to be paid.
     *
     * @param float $total The total amount.
     *
     * @throws InvalidArgumentException If the total amount is negative.
     */
    public function setTotal(float $total): void
    {
        if ($total < 0) {
            throw new InvalidArgumentException('Total amount must be non-negative.');
        }
        $this->total = $total;
    }

    /**
     * Get the creation time of the payment.
     *
     * @return DateTime The creation time.
     */
    public function getCreationTime(): DateTime
    {
        return $this->creationTime;
    }

    /**
     * Set the creation time of the payment.
     *
     * @param DateTime $creationTime The creation time to set.
     */
    public function setCreationTime(DateTime $creationTime): void
    {
        $this->creationTime = $creationTime;
    }

    /**
     * Get the payment state.
     *
     * @return StatoPagamento The payment state.
     */
    public function getState(): StatoPagamento
    {
        return $this->state;
    }

    /**
     * Set the payment state.
     *
     * @param StatoPagamento $state The payment state to set.
     */
    public function setState(StatoPagamento $state): void
    {
        $this->state = $state;
    }

    /**
     * Get the ID of the associated credit card.
     *
     * @return int|null The ID of the associated credit card, or null if not set.
     */
    public function getIdCreditCard(): ?int
    {
        return $this->idCreditCard;
    }

    /**
     * Set the ID of the associated credit card.
     *
     * @param int|null $idCreditCard The ID of the associated credit card, or null to unset.
     */
    public function setIdCreditCard(?int $idCreditCard): void
    {
        $this->idCreditCard = $idCreditCard;
    }

    /**
     * Get the ID of the associated reservation.
     *
     * @return int The ID of the associated reservation.
     */
    public function getIdReservation(): int
    {
        return $this->idReservation;
    }

    /**
     * Set the ID of the associated reservation.
     *
     * @param int $idReservation The ID of the associated reservation.
     */
    public function setIdReservation(int $idReservation): void
    {
        $this->idReservation = $idReservation;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array The data to be serialized.
     */
    public function jsonSerialize(): array
    {
        return [
            'idPayment' => $this->idPayment,
            'total' => $this->total,
            'creationTime' => $this->creationTime->format('Y-m-d H:i:s'),
            'state' => $this->state->value,
            'idCreditCard' => $this->idCreditCard,
            'idReservation' => $this->idReservation,
        ];
    }
}
