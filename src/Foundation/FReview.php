<?php
namespace Foundation;

use DateTime;
use Entity\EReview;
use Foundation\FUser;
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

    // Error messages centralized for consistency
    protected const ERR_MISSING_FIELD= 'Missing required field:';
    protected const ERR_INVALID_USER = "Invalid or non-existing user.";
    protected const ERR_EMPTY_BODY = "Review body cannot be empty.";
    protected const ERR_INVALID_STARS = "Stars rating must be a number between 1 and 5.";
    protected const ERR_INSERTION_FAILED = 'Error during the insertion of the review.';
    protected const ERR_RETRIVE_REVIEW ='Failed to retrive the inserted review.';
    protected const ERR_REVIEW_NOT_FOUND = 'The review does not exist.';
    protected const  ERR_UPDATE_FAILED = 'Error during the update operation.';

    /**
     * Creates an EReview entity from the provided data.
     *
     * @param array $data The data used to create the EReview object.
     * @return EReview The created review object.
     * @throws Exception If an error occurs during the creation of the entity.
     */
    public static function arrayToEntity(array $data):EReview {
            $requiredFields = ['idUser', 'stars', 'body'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception(self::ERR_MISSING_FIELD . $field);
            }
        }
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
     * Converts an EReview object into an associative array for the database.
     *
     * @param EReview $review The review object to convert.
     * @return array The review data as an array.
     */
    private function entityToArray(EReview $review): array {
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
     * Validates the data for creating or updating a review.
     *
     * @param array $data The data array containing review information.
     *
     * @throws Exception If required fields are missing or invalid.
     */
    public static function validateReviewData(array $data): void {
        // idUser must exist
        if (!isset($data['idUser']) || !is_int($data['idUser']) || !FUser::exists($data['idUser'])) {
            throw new Exception(self::ERR_INVALID_USER);
        }
        // Body must not be empty
        if (empty($data['body']) || !is_string($data['body'])) {
            throw new Exception(self::ERR_EMPTY_BODY);
        }

        // Stars must be a number between 1 and 5
        if (!isset($data['stars']) || !is_numeric($data['stars']) || $data['stars'] < 1 || $data['stars'] > 5) {
            throw new Exception(self::ERR_INVALID_STARS);
        }
    }

    /**
     * Creates a new review in the database.
     *
     * @param EReview $review The review object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     */
    public function create(EReview $review): int {
        $db = FDatabase::getInstance();
        $data = $this->entityToArray($review);
        self::validateReviewData($data);
        try {
            //Review insertion
            $result = $db->insert(self::TABLE_NAME, $data);
            if ($result === null) {
                throw new Exception(self::ERR_INSERTION_FAILED);
            }
            //Retrive the inserted review by number to get the assigned idReview
            $storedReview = $db->load(self::TABLE_NAME, 'number', $review->getIdReview());
            if ($storedReview === null) {
                throw new Exception(self::ERR_RETRIVE_REVIEW);
            }
            //Assign the retrieved ID to the object
            $review->setIdReview($storedReview['idReview']);
            //Return the id associated with this review
            return $storedReview['idReview'];
        } catch (Exception $e) {
            throw $e;
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
        return $result ? $this->arrayToEntity($result) : null;
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
        if (!self::exists($review->getIdReview())) {
            throw new Exception(self::ERR_REVIEW_NOT_FOUND);
        }
        $data = [
            'stars' => $review->getStars(),
            'body' => $review->getBody(),
        ];
        self::validateReviewData($data);
        if (!$db->update(self::TABLE_NAME, $data, ['idReview' => $review->getIdReview()])) {
            throw new Exception(self::ERR_UPDATE_FAILED);
        }
        return true;
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
    public static function exists(int $idReview): bool {
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
     * Loads the review written by a specific user.
     *
     * @param int $idUser The ID of the user.
     * @return EReview|null The review if found, null otherwise.
     * @throws Exception If an error occurs during the loading.
     */
    public static function loadReviewByUserId(int $idUser): ?EReview {
        $db = FDatabase::getInstance();
        $result = $db->load(self::TABLE_NAME, 'idUser', $idUser); // carica una sola review

        return $result ? self::arrayToEntity($result) : null;
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