<?php
namespace Controller;

use DateTime;
use Entity\EReply;
use Entity\EReview;
use Exception;
use Foundation\FPersistentManager;
use Foundation\FReservation;
use Foundation\FReview;
use Foundation\FReply;
use Utility\UHTTPMethods;
use Utility\USessions;
use View\VReply;
use View\VReview;

class CReply {

    /**
     * Function to show Reply form
     */
    public function showReplyForm($idReview) {
        CReview::showReviewsPage($idReview);
    }

    /**
     * Function to validate and create a new reply
     */
    public function addReply($idReview) {
        $view=new VReview();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        $body=UHTTPMethods::post('replyBody');
        //Creo un nuovo oggetto Entity con questo body
        $newReply=new EReply(
            null,
            new DateTime(),
            $body
        );
        //Aggiungo la risposta associata a questa recensione su db
        $idReply=FPersistentManager::getInstance()->create($newReply);
        //Se la risposta è stata salvata correttamente
        if($idReply!==null) {
            //Recupero la recensione associata da db
            $review=FPersistentManager::getInstance()->read($idReview, FReview::class);
            //Associo l'id della risposta alla recensione
            $review->setIdReply($idReply);
            //Salvol l'update nel DB
            FPersistentManager::getInstance()->update($review);
        }
            header("Location: /IlRitrovo/public/Review/showReviewsPage");
        }
    }
    
