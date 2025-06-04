<?php
namespace Controller;

use DateTime;
use Entity\EReply;
use Entity\EReview;
use Exception;
use Foundation\FPersistentManager;
use Foundation\FReservation;
use Foundation\FReview;
use Utility\UHTTPMethods;
use Utility\USessions;

class CReply {

    /**
     * Crea una nuova reply
     */
    public function createReply(int $idReview) {
        if(!CUser::isLogged()) {
            header("Location: HomePage");
        }
        $idUser=USessions::getSessionElement('idUser');
        $reply=new EReply(
            null,
            new DateTime(),
            UHTTPMethods::post('body')
        );
    }
    
}