<?php

namespace Entity;

use DateTime;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class EReservation
 * Represents a reservation in the system.
 */
class EReservation implements JsonSerializable {
    /**
     * IDENTIFIERS
     * @var ?int ReservationId, the Id associated with the reservation
     */
    private ?int $idReservation;

    /**
     * @var ?int UserId, the Id associated with the user
     */
    private ?int $idUser;

    /**
     * @var ?int AreaId, the Id associated with the area
     */
    private ?int $idArea; //Da cambiare in idArea
    
    /**
     * METADATA
     * @var DateTime creation time of the reservation
     */
    private DateTime $creationTime;
    
    /**
     * @var DateTime time frame of the reservation
     */
    private DateTime $reservationDate;

    /**
     * @var string reservation's statement
     */
    private string $state;

    /**
     * @var array array containing the possible states of a reservation
     */
    private const VALID_STATES = ['confirmed', 'approved', 'pending', 'canceled'];

    /**
     * @var float total amount in money of the reservation
     */
    private float $totPrice;

    

    /**
     * Constructor for the EReservation class with validation checks.
     *
     * @param ?int $idReservation The ID of the reservation.
     * @param ?int $idUser The ID of the user who made the reservation.
     * @param ?int $idArea The ID of the Area reserved.
     * @param DateTime $creationTime The creation timestamp of the reservation.
     * @param DateTime $reservationDate The date of the reservation.
     * @param string $state The state of the reservation (e.g., confirmed, approved).
     * @param float $totPrice The total price of the reservation.
     * @throws InvalidArgumentException If the state is invalid or if the reservation date is not in the future.
     */
    public function __construct(
        ?int $idReservation,
        ?int $idUser,
        ?int $idArea,
        DateTime $creationTime,
        DateTime $reservationDate,
        string $state,
        float $totPrice,
    ) {
        if (empty($state) || !in_array($state, self::VALID_STATES)) {
            throw new InvalidArgumentException("Invalid reservation state: $state");
        }
        $this->idReservation = $idReservation;
        $this->idUser = $idUser;
        $this->idArea = $idArea;
        $this->creationTime = $creationTime;
        $this->reservationDate = $reservationDate;
        $this->state = $state;
        $this->totPrice = $totPrice;
    }

    /**
     * Get the reservation ID.
     *
     * @return ?int The reservation ID.
     */
    public function getIdReservation(): ?int {
        return $this->idReservation;
    }

    /**
     * Set the reservation ID.
     *
     * @param ?int $idReservation The reservation ID.
     */
    public function setIdReservation(?int $idReservation): void {
        $this->idReservation = $idReservation;
    }

    /**
     * Get the user ID associated with the reservation.
     *
     * @return ?int The user ID.
     */
    public function getIdUser(): ?int {
        return $this->idUser;
    }

    /**
     * Set the user ID associated with the reservation.
     *
     * @param ?int $idUser The user ID.
     */
    public function setIdUser(?int $idUser): void {
        $this->idUser = $idUser;
    }

    /**
     * Get the room ID associated with the reservation.
     *
     * @return ?int The room ID.
     */
    public function getIdArea(): ?int {
        return $this->idArea;
    }

    /**
     * Set the room ID associated with the reservation.
     *
     * @param ?int $idRoom The room ID.
     */
    public function setIdArea(?int $idRoom): void {
        $this->idArea = $idRoom;
    }

    /**
     * Get the creation time of the reservation.
     *
     * @return DateTime The creation time.
     */
    public function getCreationTime(): DateTime {
        return $this->creationTime;
    }

    /**
     * Set the creation time of the reservation.
     *
     * @param DateTime $creationTime The creation time.
     */
    public function setCreationTime(DateTime $creationTime): void {
        $this->creationTime = $creationTime;
    }

    /**
     * Get the reservation date.
     *
     * @return DateTime The reservation date.
     */
    public function getReservationDate(): DateTime {
        return $this->reservationDate;
    }

    /**
     * Set the reservation date.
     *
     * @param DateTime $reservationDate The reservation date.
     * @throws InvalidArgumentException If the reservation date is not in the future.
     */
    public function setReservationDate(DateTime $reservationDate): void {
        if ($reservationDate <= $this->creationTime) {
            throw new InvalidArgumentException("Reservation date must be in the future.");
        }
        $this->reservationDate = $reservationDate;
    }

    /**
     * Get the state of the reservation.
     *
     * @return string The reservation state.
     */
    public function getState(): string {
        return $this->state;
    }

    /**
     * Set the state of the reservation.
     *
     * @param string $state The reservation state.
     * @throws InvalidArgumentException If the state is invalid.
     */
    public function setState(string $state): void {
        if (!in_array($state, self::VALID_STATES)) {
            throw new InvalidArgumentException("Invalid reservation state: $state");
        }
        $this->state = $state;
    }

    /**
     * Get the total price of the reservation.
     *
     * @return float The total price.
     */
    public function getTotPrice(): float {
        return $this->totPrice;
    }

    /**
     * Set the total price of the reservation.
     *
     * @param float $totPrice The total price.
     */
    public function setTotPrice(float $totPrice): void {
        $this->totPrice = $totPrice;
    }

    /**
     * Implementation of the jsonSerialize method.
     *
     * @return array Associative array of the object's properties.
     */
    public function jsonSerialize(): array {
        return [
            'idReservation' => $this->idReservation,
            'idUser' => $this->idUser,
            'idArea' => $this->idArea,
            'creationTime' => $this->creationTime->format('Y-m-d H:i:s'),
            'reservationDate' => $this->reservationDate->format('Y-m-d H:i:s'),
            'state' => $this->state,
            'totPrice' => $this->totPrice,
        ];
    }
}
