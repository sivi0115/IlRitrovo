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
    
}