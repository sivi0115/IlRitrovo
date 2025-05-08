<?php
namespace Foundation;

use DateTime;
use Entity\EReview;
use Exception;

class FReview
{
    protected const TABLE_NAME = 'review';

    public function __construct() {}

    /**
     * Crea una nuova istanza di EReview con i dati forniti.
     *
     * @param int|null $idUser
     * @param int $idReview
     * @param int $idReply
     * @param int $stars
     * @param string $body
     * @param DateTime $creationTime
     * @param bool $removed
     * @return EReview
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
     * Stores a new review in the database.
     *
     * @param EReview $review The review to store.
     * @return int The ID of the newly inserted review.
     * @throws Exception If an error occurred during the insertion.
     */
    public static function storeReview(EReview $review): int {
        $db = FDatabase::getInstance();
        $data = [
            'idUser' => $review->getIdUser(),
            'idReview' => $review->getIdReview(),
            'idReply' => $review->getIdReply(),
            'stars' => $review->getStars(),
            'body' => $review->getBody(),
            'creationTime' => $review->getCreationTime(),
            'removed' => $review->getRemoved(),
        ];

        $id = $db->insert(self::TABLE_NAME, $data);
        if ($id === null) {
            throw new Exception('Errore durante l\'inserimento della recensione.');
        }
        return $id;
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

        $creationTime = new DateTime($row['creationTime']); // Converte la stringa in DateTime

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
     * Deletes a review by its ID.
     *
     * @param int $idReview The ID of the review to delete.
     * @return true
     * @throws Exception If an error occurs during the deletion.
     */
    public static function deleteReview(int $idReview): bool
    {
        $db = FDatabase::getInstance();
        $deleted = $db->delete(self::TABLE_NAME, ['idReview' => $idReview]);
        if (!$deleted) {
            throw new Exception('Errore durante l\'eliminazione della recensione.');
        }
        return true;
    }

    /**
     * Loads all reviews from the database.
     *
     * @return EReview[] An array of EReview objects or an empty array if none are found.
     * @throws Exception If an error occurs during the loading of reviews.
     */
    public static function loadAllReview(): array {
        $db = FDatabase::getInstance();
        $result = $db->loadMultiples(self::TABLE_NAME);
        return self::mapResultsToReviews($result);
    }

    /**
     * Loads a review by its ID.
     *
     * @param int $idReview The ID of the review to load.
     * @return EReview|null The loaded review or null if not found.
     * @throws Exception If an error occurs during the loading of the review.
     */
    public static function loadReview(int $idReview): ?EReview {
        $db = FDatabase::getInstance();
        $row = $db->load(self::TABLE_NAME, 'idReview', $idReview);
        if ($row === null) {
            return null;
        }
        return (new FReview)->mapRowToReview($row);
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

    public static function existsReview(int $idReview): bool {
        $db = FDatabase::getInstance();
        $exists = $db->exists(self::TABLE_NAME, ['idReview' => $idReview]);
        return $exists;
    }


    /**
     * Updates an existing review in the database.
     *
     * @param EReview $review The review object containing the updated data.
     * @return void
     * @throws Exception If there is an error during the update process.
     */
    public static function updateReview(EReview $review): void {
        $db = FDatabase::getInstance();
        $data = [
            'body' => $review->getBody(),
            'creationTime' => $review->getCreationTime(),
            'removed' => (int)$review->getRemoved(),
            'stars' => $review->getStars(),
            'idUser' => $review->getIdUser(),
        ];

        $updated = $db->update(self::TABLE_NAME, $data, ['idReview' => $review->getIdReview()]);
        if (!$updated) {
            throw new Exception('Errore durante l\'aggiornamento della recensione.');
        }
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

    /**
     * Replies to a review by updating its reply field.
     *
     * @param int $idReview The ID of the review to reply to.
     * @param string $reply The reply text.
     * @return void
     * @throws Exception If an error occurs during the reply update.
     */
    public static function replyToReview(int $idReview, string $reply): void {
        $db = FDatabase::getInstance();

        $data = ['reply' => $reply];
        $updated = $db->update(self::TABLE_NAME, $data, ['idReview' => $idReview]);
        if (!$updated) {
            throw new Exception('Errore durante l\'inserimento della risposta alla recensione.');
        }
    }

    /**
     * Retrieves a review by its ID.
     *
     * @param int $idReview The ID of the review to retrieve.
     * @return EReview|null The retrieved review or null if not found.
     * @throws Exception If an error occurs during the retrieval.
     */
    public static function getReviewById(int $idReview): ?EReview {
        $db = FDatabase::getInstance();

        $row = $db->load(self::TABLE_NAME, 'idReview', $idReview);
        if ($row === null) {
            return null;
        }

        return (new FReview)->mapRowToReview($row);
    }

    /**
     * Bans a review by setting its "removed" field to true.
     *
     * @param int $idReview The ID of the review to ban.
     * @return void
     * @throws Exception If an error occurs during the update.
     */
    public static function banReview(int $idReview): void {
        $db = FDatabase::getInstance();

        // Update the "removed" field to true (1 in database).
        $data = ['removed' => 1];
        $updated = $db->update(self::TABLE_NAME, $data, ['idReview' => $idReview]);

        if (!$updated) {
            throw new Exception('Errore durante il ban della recensione.');
        }
    }


}
