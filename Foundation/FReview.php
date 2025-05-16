<?php
namespace Foundation;

use DateTime;
use Entity\EReview;
use Exception;

/**
 * Class FReview
 * Handles CRUD and others operations for reviews in the database.
 */
class FReview {
    /**
     * Name of the table associated with the review entity in the database.
     */
    protected const TABLE_NAME = 'review';

    /**
     * Returns the name of the table associated with reviews.
     *
     * @return string The name of the table.
     */
    public function getTableName(): string {
        return static::TABLE_NAME;
    }

    /**
     * Converts an EReview object into an associative array for the database.
     *
     * @param EReview $review The review object to convert.
     * @return array The review data as an array.
     */
    private function reviewToArray(EReview $review): array {
        return [
            'idUser' => $review->getIdUser(),
            'idReview' => $review->getIdReview(),
            'idReply' => $review->getIdReply(),
            'stars' => $review->getStars(),
            'body' => $review->getBody(),
            'creationTime' => $review->getCreationTime(),
            'removed' => $review->getRemoved(),
        ];
    }

    /**
     * Creates an EReview entity from the provided data.
     *
     * @param array $data The data used to create the EReview object.
     * @return EReview The created review object.
     * @throws Exception If an error occurs during the creation of the entity.
     */
    public static function createEntityReview(array $data):EReview {
        return new EReview(
            $data['idUser'],
            $data['idReview'],
            $data['idReply'],
            $data['stars'],
            $data['body'],
            $data['creationTime'],
            $data['removed']
        );
    }

    /**
     * Creates a new review in the database.
     *
     * @param EReview $review The review object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     */
    public function create(EReview $review): int {
        try {
            $db = FDatabase::getInstance();
            $data = $this->reviewToArray($review);
            return $db->insert(static::TABLE_NAME, $data) ?: 0;
        } catch (Exception $e) {
            error_log("Errore durante l'inserimento della recensione: " . $e->getMessage());
            return 0; // Or use a different error handling approach
        }
    }

    /**
     * Reads a review from the database by their ID.
     *
     * @param int $id The ID of the review to read.
     * @return EReview|null The review object if found, null otherwise.
     * @throws Exception
     */
    public function read(int $id): ?EReview {
        $db = FDatabase::getInstance();
        $result = $db->load(static::TABLE_NAME, 'idReview', $id);
        return $result ? $this->createEntityReview($result) : null;
    }

    /**
     * Updates an existing review in the database.
     *
     * @param EReview $review The review object to update.
     * @param int $id The ID of the review to update.
     * @return bool True if the update was successful, False otherwise.
     */
    public function update(EReview $review): bool {
        $db = FDatabase::getInstance();
        $data = $this->reviewToArray($review);
        return $db->update(static::TABLE_NAME, $data, ['idReview' => $review->getIdReview()]);
    }

    /**
     * Deletes a review from the database by their ID.
     *
     * @param int $id The ID of the review to delete.
     * @return bool True if the deletion was successful, False otherwise.
     */
    public function delete(int $id): bool {
        $db = FDatabase::getInstance();
        return $db->delete(static::TABLE_NAME, ['idReview' => $id]);
    }

    /**
     * Logically bans (removes) a review by setting its "removed" flag to true.
     *
     * @param int $idReview The ID of the review to be banned.
     * @return bool True if the review was successfully updated.
     * @throws Exception If the review with the given ID is not found.
     */
    public function banReview(int $idReview): bool {
        $review = $this->read($idReview);
        if (!$review) {
            throw new Exception("Recensione non trovata.");
        }
        $review->setRemoved(true);
        return $this->update($review);
    }

    /**
     * Checks if a review exists in the database based on a specific field.
     *
     * @param string $field The field to check (e.g., 'idUser', 'body').
     * @param mixed $value The value of the field.
     * @return bool True if it exists, False otherwise.
     */
    public static function existsReview(int $idReview): bool {
        $db = FDatabase::getInstance();
        $exists = $db->exists(self::TABLE_NAME, ['idReview' => $idReview]);
        return $exists;
    }

    /**
     * Maps the database results to an array of EReview objects.
     *
     * @param array $results The database results.
     * @return EReview[] An array of EReview objects.
     * @throws Exception If an error occurs during the mapping.
     */
    private static function mapResultsToReviews(array $results): array {
        $reviews = [];
        foreach ($results as $row) {
            $reviews[] = (new FReview)->mapRowToReview($row);
        }
        return $reviews;
    }

    /**
     * Maps a database row to an EReview object.
     *
     * @param array $row The database row.
     * @return EReview The created review object.
     * @throws Exception If an error occurs during the mapping.
     */
    private function mapRowToReview(array $row): EReview {
        $creationTime = new DateTime($row['creationTime']); // Convert the string to DateTime
        $review = new EReview(
            $row['idUser'] ?? null,
            $row['idReview'] ?? null,
            $row['idReply'] ?? null,
            (int)$row['stars'],
            $row['body'],
            $creationTime,
            $row['removed'],
        );
        $review->setCreationTime(new DateTime($row['creationTime']));
        $review->setRemoved((bool)$row['removed']);
        return $review;
    }

    /**
     * Loads all reviews from the database.
     *
     * @return EReview[] An array of EReview objects or an empty array if none are found.
     * @throws Exception If an error occurs during the loading of reviews.
     */
    public static function loadAllReviews(): array {
        $db = FDatabase::getInstance();
        $result = $db->loadMultiples(self::TABLE_NAME);
        return self::mapResultsToReviews($result);
    }

    /**
     * Loads reviews by user ID.
     *
     * @param int $idUser The ID of the user.
     * @return EReview[] An array of EReview objects.
     * @throws Exception If an error occurs during the loading of reviews.
     */
    public static function loadReviewByUserId(int $idUser): array {
        $db = FDatabase::getInstance();
        $conditions = ['idUser' => $idUser];
        $result = $db->loadMultiples(self::TABLE_NAME, $conditions);
        return $result ? self::mapResultsToReviews($result) : [];
    }

    /**
     * Associates a reply with a review by setting the reply ID.
     *
     * @param int $idReview The ID of the review to update.
     * @param int $idReply The ID of the reply to associate.
     * @return bool True if the update was successful.
     * @throws Exception If the review is not found.
     */
    public function addReplyToReview(int $idReview, int $idReply): bool {
        $review = $this->read($idReview);
        if (!$review) {
            throw new Exception("La recensione a cui si vuole rispondere non è stata trovata.");
        }
        $review->setIdReply($idReply);
        return $this->update($review);
    }

    /**
     * Retrieves reviews created on the specified date.
     *
     * @param DateTime $date The date to filter reviews by.
     * @return EReview[] An array of EReview objects created on the specified date.
     * @throws Exception If there is an error during the retrieval process.
     */
    public static function getReviewsByDate(DateTime $date): array {
        $db = FDatabase::getInstance();
        $conditions = ['DATE(creationTime)' => $date->format('Y-m-d')];
        $result = $db->loadMultiples(self::TABLE_NAME, $conditions);
        return $result ? self::mapResultsToReviews($result) : [];
    }
}