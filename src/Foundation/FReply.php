<?php
namespace Foundation;

use DateTime;
use Entity\EReply;
use Exception;

/**
 * Class FReply
 * Handles CRUD and others operations for replies to reviews in the database.
 */
class FReply {
    /**
     * Name of the table associated with the reply entity in the database.
     */
    protected const TABLE_NAME = 'reply';

    /**
     * Returns the name of the table associated with replies.
     *
     * @return string The name of the table.
     */
    public function getTableName(): string {
        return static::TABLE_NAME;
    }

    // Error messages centralized for consistency
    protected const ERR_MISSING_FIELD= 'Missing required field:';

    /**
     * Creates a new instance of EReply with the provided data
     * 
     * @param int $idReply
     * @param DateTime $dateReply
     * @param string $body
    */
    public function arrayToEntity(array $data): EReply {
            $requiredFields = ['idReply', 'dateReply', 'body'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception(self::ERR_MISSING_FIELD . $field);
            }
        }
        return new EReply(
            $data['idReply'] ?? null,
            $data['dateReply'] ?? null,
            $data['body'] ?? null
        );
    }

    /**
     * Converts an EReply object into an associative array for the database.
     *
     * @param EReply $reply The reply object to convert.
     * @return array The reply data as an array.
     */
    public function entityToArray(EReply $reply): array {
        return [
            'idReply' => $reply->getIdReply(),
            'dateReply' => $reply->getDateReply(),
            'body' => $reply->getBody(),
        ];
    }

    /**
     * Creates a new reply in the database.
     *
     * @param EReply $reply The reply object to save.
     * @return int The ID of the saved record, or 0 if the save fails.
     */
    public function create(EReply $reply): int {
        try {
            $db = FDatabase::getInstance();
            $data = $this->entityToArray($reply);
            return $db->insert(static::TABLE_NAME, $data) ?: 0;
        } catch (Exception $e) {
            error_log("Errore durante l'inserimento della risposta: " . $e->getMessage());
            return 0; // Or use a different error handling approach
        }
    }

    /**
     * Reads a reply from the database by their ID.
     *
     * @param int $id The ID of the reply to read.
     * @return EReply|null The reply object if found, null otherwise.
     * @throws Exception
     */
    public function read(int $id): ?EReply {
        $db = FDatabase::getInstance();
        $result = $db->load(static::TABLE_NAME, 'idReply', $id);
        return $result ? $this->createEntityReply($result) : null;
    }

    /**
     * Updates an existing reply in the database.
     *
     * @param EReply $reply The reply object to update.
     * @param int $id The ID of the reply to update.
     * @return bool True if the update was successful, False otherwise.
     */
    public function update(EReply $reply): bool {
        $db = FDatabase::getInstance();
        $data = $this->replyToArray($reply);
        return $db->update(static::TABLE_NAME, $data, ['idReply' => $reply->getIdReply()]);
    }

    /**
     * Deletes a reply from the database by their ID.
     *
     * @param int $id The ID of the reply to delete.
     * @return bool True if the deletion was successful, False otherwise.
     */
    public function delete(int $id): bool {
        $db = FDatabase::getInstance();
        return $db->delete(static::TABLE_NAME, ['idReply' => $id]);
    }

    /**
     * Checks if a reply exists in the database based on a specific field.
     *
     * @param string $field The field to check (e.g., 'idReply', 'body').
     * @param mixed $value The value of the field.
     * @return bool True if it exists, False otherwise.
     */
    public static function existsReply(int $idReply): bool {
        $db = FDatabase::getInstance();
        $exists = $db->exists(self::TABLE_NAME, ['idReply' => $idReply]);
        return $exists;
    }
}