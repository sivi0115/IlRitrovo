<?php

namespace Controller;

use DateTime;
use Entity\EReply;
use Entity\EReview;
use Exception;
use Foundation\FPersistentManager;
use Foundation\FReservation;
use Foundation\FReview;
use Foundation\FUser;
use Utility\UHTTPMethods;
use Utility\USessions;
use View\VUser;
use View\VReview;

class CReview {
    /**
     * Constructor
     */
    public function __construct() {
    }
    /**
     * Function to add a Review (past reservation needed)
     */
    public function checkAddReview() {
        $view=new VUser();
        $session=USessions::getIstance();
        $session->startSession();
        //Carico dalla sessione l'id dell'utente
        $idUser=$session->readValue('idUser');
        //Creo un'oggetto Review con i dati provenienti dalla form HTML
        $newReview=new EReview(
            $idUser,
            null,
            UHTTPMethods::post('stars'),
            UHTTPMethods::post('body'),
            new DateTime(),
            null
        );
        //Salvo la recensione creata e reindirizzo alla schermata informazioni. Tutte le validazioni sono affidate a foundation
        try {
            FPersistentManager::getInstance()->create($newReview);
            header("Location: /IlRitrovo/public/User/showProfile");
        } catch (Exception $e) {
            echo "Operazione non effettuata" . $e->getMessage();
            //$view->showAddReviewError;
        }
    }

    /**
     * Function to delete a review
     * 
     * @param $idReview taken from the HTTP request
     * @return bool true if success, false otherwise
     */
    public function deleteReview($idReview) {
        $deleted=FPersistentManager::getInstance()->delete($idReview, FReview::class);
        if($deleted) {
            header("Location: /IlRitrovo/public/User/showProfile");
        exit;
        } else {
            echo "Errore nell'esecuzione della cancellazione";
        }
    }

    /**
     * Function to show the review's page
     */
    public function showReviewsPage() {
        $viewU=new VUser();
        $viewR=new VReview();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        //Carico l'oggetto EUser dal suo id
        $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
        //Carico tutte le recensioni esistenti
        $allReviews=FPersistentManager::getInstance()->readAll(FReview::class);
        //Per ogni recensione carico l'utente relativo per ottenere l'username
        foreach($allReviews as $review) {
            $idReviewUser=$review->getIdUser();
            $reviewUser=FPersistentManager::getInstance()->read($idReviewUser, FUser::class);
            //Associo l'username ad ogni recensione
            if($reviewUser!==null) {
                $review->setUsername($reviewUser->getUsername());
            } else {
                //Se per qualche motivo l'utente non esiste
                $review->setUsername('Unknown User');
            }
        }
        //Se l'utente è un admin, visualizzerà la pagina recensioni dell'admin con relativo header
        if($user->isAdmin()) {
            $viewU->showAdminHeader($isLogged);
            $viewR->showReviewsAdminPage($allReviews);
        } else {
            //Altrimenti visualizzerà la pagina recensioni dell'utente normale con realtivo header
            $viewU->showUserHeader($isLogged);
            $viewR->showReviewsUserPage($allReviews);
        }
    }
}