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
use View\VReservation;
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
        $viewU=new VUser();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        //Carico l'utente da db grazie al suo id User
        $user=FPersistentManager::getInstance()->read($idUser, FUser::class);
        //Elimino la recensione
        $deleted=FPersistentManager::getInstance()->delete($idReview, FReview::class);
        //Se è un utente lo reindirizzo alla schermata informazioni
        if($deleted && $user->isUser()) {
            header("Location: /IlRitrovo/public/User/showProfile");
        exit;
        }
        //Se è un admin lo reindirizzo alla schermata recensioni
        elseif($deleted && $user->isAdmin()) {
            header("Location: /IlRitrovo/public/Review/showReviewsPage");
        }
        elseif(!$deleted) {
            echo "Errore durante l'operazione";
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