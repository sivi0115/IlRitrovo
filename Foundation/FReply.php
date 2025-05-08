<?php
namespace Foundation;

use DateTime;
use Entity\EReply;
use Exception;

class FReply {
    protected const TABLE_NAME = 'reply';

    public function __construct() {}

    /**
     * Crea una nuova istanza di EReply con i dati forniti
     * 
     * @param int $idReply
     * @param DateTime $dateReply
     * @param string $body
    */

    public static function createEnityReply(array $data): EReply {
        return new EReply(
            $data['idReply'] ?? null,
            $data['dateReply'] ?? null,
            $data['body'] ?? null
        );
    }

    /**
    * Inserisce nuove Reply all'interno del database
    * 
    * @param EReply la risposta da inserire
    * @return int idReply l'id della nuova reply inserita
    * @throws Exception
    */
    public static function storeReply(EReply $reply): int {
        $db=FDatabase::getInstance();
        $data = [
            'idReply' => $reply->getIdReply(),
            'dateReply' => $reply->getDateReply(),
            'body' => $reply->getBody(),
        ];

        $id = $db->insert(self::TABLE_NAME, $data);
        if ($id === null) {
            throw new Exception('Errore durante l\'inserimento della risposta.');
        }
        return $id;
    }

    /**
     * Maps the database results to an array of EReplay objects
     * 
     * @param array $results the database results
     * @return EReply[] An array of EReply objects
     * @throws Exception If an error occurs during the mapping
     */
    private static function mapResultsToReply(array $results): array {
        $reply=[];
        foreach ($results as $row) {
            $reply[]=(new FReply)->mapRowToReply($row);
        }
        return $reply;
    }

    /**
     * Maps a database row to an EReply object
     * 
     * @param array $row the database row
     * @return EReply the created reply object
     * @throws Exception if an error occurs during the mapping
     */
    private function mapRowToReply(array $row): EReply {
        $dateReply=new Datetime($row['dateReply']);
        $reply=new EReply(
            $row['idReply'] ?? null,
            $dateReply,
            $row['body'] ?? null
        );
        return $reply;
    }

    /**
     * Deletes a reply by its ID
     * 
     * @param int $idReply of the id to delete
     * @return true
     * @throws Exception If an error occurs during the delection
     */
    public static function deleteReply(int $idReply): bool {
        $db=FDatabase::getInstance();
        $deleted=$db->delete(self::TABLE_NAME, ['idReply'=>$idReply]);
        if (!$deleted) {
            throw new Exception('Errore durante l\'eliminazione della risposta alla recensione');
        }
        return true;
    }

    /**
     * Loads all reply form the database
     * 
     * @return EReply[] an array of EReply objects or an empty array if none are found
     * @throws Exception If an error during the loading of reviews
     */
    public static function loadAllReply(): array {
        $db=FDatabase::getInstance();
        $result=$db->loadMultiples(self::TABLE_NAME);
        return self::mapResultsToReply($result);
    }

    /**
     * Loads a review by it's ID
     * 
     * @param int $idReply The ID of the reply to load
     * @return EReply|null The loaded reply or null if none founded
     * @throws Excpetion If an error occurs during the loading of reply
     */
    public static function loadReply(int $idReply): ?EReply {
        $db=FDatabase::getInstance();
        $row=$db->load(self::TABLE_NAME, 'idReply', $idReply);
        if($row===null) {
            return null;
        }
        return (new FReply)->mapRowToReply($row);
    }

    /**
     * Loads reply by user ID.
     *
     * @param int $idUser The ID of the user.
     * @return EReview[] An array of EReply objects.
     * @throws Exception If an error occurs during the loading of reply.
     */
    public static function loadReplyByUserId(int $idUser): array {
        $db = FDatabase::getInstance();
        $conditions = ['idUser' => $idUser];
        $result = $db->loadMultiples(self::TABLE_NAME, $conditions);
        return $result ? self::mapResultsToReply($result) : [];
    }

    /**
     * Updates an existing reply in the database.
     *
     * @param EReply $reply The review object containing the updated data.
     * @return void
     * @throws Exception If there is an error during the update process.
     */
    public static function updateReply(EReply $reply): void {
        $db = FDatabase::getInstance();
        $data = [
            'idReply' => $reply->getIdReply(),
            'dateReply' => $reply->getDateReply(),
            'body' => $reply->getBody()
            
        ];

        $updated = $db->update(self::TABLE_NAME, $data, ['idReply' => $reply->getIdReply()]);
        if (!$updated) {
            throw new Exception('Errore durante l\'aggiornamento della recensione.');
        }
    }

    /**
     * Retrieves reply created on the specified date.
     *
     * @param DateTime $date The date to filter reviews by.
     * @return EReview[] An array of EReply objects created on the specified date.
     * @throws Exception If there is an error during the retrieval process.
     */
    public static function getReplyByDate(DateTime $date): array {
        $db = FDatabase::getInstance();
        $conditions = ['DATE(creationTime)' => $date->format('Y-m-d')];
        $result = $db->loadMultiples(self::TABLE_NAME, $conditions);
        return $result ? self::mapResultsToReply($result) : [];
    }

    /**
     * Retrieves a reply by its ID.
     *
     * @param int $idReply The ID of the reply to retrieve.
     * @return EReply|null The retrieved reply or null if not found.
     * @throws Exception If an error occurs during the retrieval.
     */
    public static function getReplyById(int $idReply): ?EReply {
        $db = FDatabase::getInstance();

        $row = $db->load(self::TABLE_NAME, 'idReply', $idReply);
        if ($row === null) {
            return null;
        }

        return (new FReply)->mapRowToReply($row);
    }


    
}