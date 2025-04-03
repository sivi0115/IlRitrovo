<?php

namespace Entity;

use DateTime;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class EReview
 * Represents a review entity with properties such as body, stars, user, and location.
 */
class EReview implements JsonSerializable
{
    /** @var int|null $idReview The ID of the review */
    private ?int $idReview;

    /** @var string $body The text body of the review */
    private string $body;

    /** @var DateTime $creationTime The creation timestamp of the review */
    private DateTime $creationTime;

    /** @var bool $removed Whether the review has been removed */
    private bool $removed = false;

    /** @var int $stars The rating given in the review, between 1 and 5 */
    private int $stars;

    /** @var int|null $idUser The ID of the user who made the review */
    private ?int $idUser;

    /**
     * EReview constructor.
     *
     * @param int|null $idReview The ID of the review.
     * @param string $body The text body of the review.
     * @param DateTime $creationTime The creation timestamp of the review.
     * @param bool $removed Whether the review has been removed.
     * @param int $stars The rating given in the review, between 1 and 5.
     * @param int|null $idUser The ID of the user who made the review.
     *
     * @throws InvalidArgumentException If the number of stars is not between 1 and 5.
     */
    public function __construct(
        ?int $idReview,
        string $body,
        DateTime $creationTime,
        bool $removed,
        int $stars,
        ?int $idUser
    ) {
        if ($stars < 1 || $stars > 5) {
            throw new InvalidArgumentException("Stars must be between 1 and 5.");
        }

        $this->idReview = $idReview;
        $this->body = $body;
        $this->creationTime = $creationTime;
        $this->removed = $removed;
        $this->stars = $stars;
        $this->idUser = $idUser;
    }

    /**
     * Gets the ID of the review.
     *
     * @return int|null The ID of the review.
     */
    public function getIdReview(): ?int
    {
        return $this->idReview;
    }

    /**
     * Sets the ID of the review.
     *
     * @param int|null $idReview The ID of the review.
     */
    public function setIdReview(?int $idReview): void
    {
        $this->idReview = $idReview;
    }

    /**
     * Gets the text body of the review.
     *
     * @return string The text body of the review.
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sets the text body of the review.
     *
     * @param string $body The text body of the review.
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * Gets the creation timestamp of the review.
     *
     * @return DateTime The creation timestamp of the review.
     */
    public function getCreationTime(): DateTime
    {
        return $this->creationTime;
    }

    /**
     * Sets the creation timestamp of the review.
     *
     * @param DateTime $creationTime The creation timestamp of the review.
     */
    public function setCreationTime(DateTime $creationTime): void
    {
        $this->creationTime = $creationTime;
    }

    /**
     * Gets the removal status of the review.
     *
     * @return bool Whether the review has been removed.
     */
    public function getRemoved(): bool
    {
        return $this->removed;
    }

    /**
     * Sets the removal status of the review.
     *
     * @param bool $removed Whether the review has been removed.
     */
    public function setRemoved(bool $removed): void
    {
        $this->removed = $removed;
    }

    /**
     * Gets the star rating of the review.
     *
     * @return int The star rating of the review.
     */
    public function getStars(): int
    {
        return $this->stars;
    }

    /**
     * Sets the star rating of the review.
     *
     * @param int $stars The star rating of the review, between 1 and 5.
     *
     * @throws InvalidArgumentException If the number of stars is not between 1 and 5.
     */
    public function setStars(int $stars): void
    {
        if ($stars < 1 || $stars > 5) {
            throw new InvalidArgumentException("Stars must be between 1 and 5.");
        }
        $this->stars = $stars;
    }

    /**
     * Gets the user ID associated with the review.
     *
     * @return int|null The ID of the user who made the review.
     */
    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    /**
     * Sets the user ID associated with the review.
     *
     * @param int|null $idUser The ID of the user who made the review.
     */
    public function setIdUser(?int $idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * Serializes the review object into an associative array for JSON encoding.
     *
     * @return array The serialized representation of the review object.
     */
    public function jsonSerialize(): array
    {
        return [
            'idReview' => $this->idReview,
            'body' => $this->body,
            'creationTime' => $this->creationTime->format('Y-m-d H:i:s'),
            'removed' => $this->removed,
            'stars' => $this->stars,
            'idUser' => $this->idUser,
        ];
    }
}
