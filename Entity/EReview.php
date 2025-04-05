<?php

namespace Entity;
use DateTime;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class EReview
 * Represents a review entity with properties.
 */
class EReview implements JsonSerializable {
    /** 
     * IDENTIFIERS
     * @var int|null $idUser The ID of the user who made the review 
     */
    private ?int $idUser;

    /** 
     * @var int|null $idReview The ID of the review 
     */
    private ?int $idReview;

    /** 
     * @var int|null $idReply The ID of the reply, initially it's null, can be changed wit setIdReply 
     */
    private ?int $idReply;

    /**
     * METADATA 
     * @var int $stars The rating given in the review, between 1 and 5 
     */
    private int $stars;

    /**
     * @var string $body The text body of the review 
     */
    private string $body;

    /** 
     * @var DateTime $creationTime The creation timestamp of the review 
     */
    private DateTime $creationTime;

    /** 
     * @var bool $removed Whether the review has been removed 
     */
    private bool $removed = false;

    

    /**
     * Constructor for the EReview class with validation checks.
     * 
     * @param int|null $idUser The ID of the user who made the review.
     * @param int|null $idReview The ID of the review.
     * @param int|null $idReply The ID of the reply if exists
     * @param int $stars The rating given in the review, between 1 and 5.
     * @param string $body The text body of the review.
     * @param DateTime $creationTime The creation timestamp of the review.
     * @param bool $removed Whether the review has been removed.
     * @throws InvalidArgumentException If the number of stars is not between 1 and 5.
     */
    public function __construct(
        ?int $idUser,
        ?int $idReview,
        int $stars,
        string $body,
        DateTime $creationTime,
        bool $removed
    ) {
        if ($stars < 1 || $stars > 5) {
            throw new InvalidArgumentException("Stars must be between 1 and 5.");
        }
        $this->idUser = $idUser;
        $this->idReview = $idReview;
        $this->stars = $stars;
        $this->body = $body;
        $this->creationTime = $creationTime;
        $this->removed = $removed;
    }

    /**
     * Gets the user ID associated with the review.
     *
     * @return int|null The ID of the user who made the review.
     */
    public function getIdUser(): ?int {
        return $this->idUser;
    }

    /**
     * Sets the user ID associated with the review.
     *
     * @param int|null $idUser The ID of the user who made the review.
     */
    public function setIdUser(?int $idUser): void {
        $this->idUser = $idUser;
    }

    /**
     * Gets the ID of the review.
     *
     * @return int|null The ID of the review.
     */
    public function getIdReview(): ?int {
        return $this->idReview;
    }

    /**
     * Sets the ID of the review.
     *
     * @param int|null $idReview The ID of the review.
     */
    public function setIdReview(?int $idReview): void {
        $this->idReview = $idReview;
    }

    /**
     * Gets the ID of the reply if exists
     * 
     * @return int|null The ID of the reply if exists
     */
    public function getIdReply(): ?int {
        return $this->idReply;
    }

    /**
     * Sets the ID of the reply
     * 
     * @param int $idReply The ID of the reply 
     */
    public function setIdReply(?int $idReply): void {
        $this->idReply=$idReply;
    }

    /**
     * Gets the star rating of the review.
     *
     * @return int The star rating of the review.
     */
    public function getStars(): int {
        return $this->stars;
    }

    /**
     * Sets the star rating of the review.
     *
     * @param int $stars The star rating of the review, between 1 and 5.
     * @throws InvalidArgumentException If the number of stars is not between 1 and 5.
     */
    public function setStars(int $stars): void {
        if ($stars < 1 || $stars > 5) {
            throw new InvalidArgumentException("Stars must be between 1 and 5.");
        }
        $this->stars = $stars;
    }

    /**
     * Gets the text body of the review.
     *
     * @return string The text body of the review.
     */
    public function getBody(): string {
        return $this->body;
    }

    /**
     * Sets the text body of the review.
     *
     * @param string $body The text body of the review.
     */
    public function setBody(string $body): void {
        if (empty($body)) {
            throw new InvalidArgumentException("Il corpo della recensione non può essere vuoto");
        }
        $this->body = $body;
    }

    /**
     * Gets the creation timestamp of the review.
     *
     * @return DateTime The creation timestamp of the review.
     */
    public function getCreationTime(): DateTime {
        return $this->creationTime;
    }

    /**
     * Sets the creation timestamp of the review.
     *
     * @param DateTime $creationTime The creation timestamp of the review.
     */
    public function setCreationTime(DateTime $creationTime): void {
        $this->creationTime = $creationTime;
    }

    /**
     * Gets the removal status of the review.
     *
     * @return bool Whether the review has been removed.
     */
    public function getRemoved(): bool {
        return $this->removed;
    }

    /**
     * Sets the removal status of the review.
     *
     * @param bool $removed Whether the review has been removed.
     */
    public function setRemoved(bool $removed): void {
        $this->removed = $removed;
    }

    /**
     * Implementation of the jsonSerialize method.
     *
     * @return array Associative array of the object's properties.
     */
    public function jsonSerialize(): array {
        return [
            'idUser' => $this->idUser,
            'idReview' => $this->idReview,
            'idReply' => $this->idReply,
            'stars' => $this->stars,
            'body' => $this->body,
            'creationTime' => $this->creationTime->format('Y-m-d H:i:s'),
            'removed' => $this->removed,
        ];
    }
}
