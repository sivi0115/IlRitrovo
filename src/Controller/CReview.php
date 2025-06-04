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

class CReview
{
    /**
     * Crea una nuova recensione.
     *
     * @return void
     * @throws Exception
     */
    public function createReview(): void {
        //Verifica che l'utente sia loggato
        if(!CUser::isLogged()) {
            header('Location: /IlRitrovo/Login');
            exit;
        }
        //Se l'utente è loggato, recupero il suo id dalla sessione
        $idUser=USessions::getSessionElement('idUser');
        //Creo un EReview con i dati provenienti dalla POST
        $review=new EReview(
            $idUser,
            null,
            UHTTPMethods::post('stars'),
            UHTTPMethods::post('body'),
            new DateTime,
            null,
        );
        //Verifico se esistono prenotazioni associate a questo utente
        if(empty(FPersistentManager::getInstance()->readReservationsByUserId($idUser, FReservation::class))) {
            throw new Exception("U can't write a Review, past reservation needed");
        }
        if(empty($review->getStars())) {
            throw new Exception("Stars field can't be empty");
        }
        if(empty($review->getBody())) {
            throw new Exception("Review's body can't be empty");
        }
        //Salvataggio nel DB in cui tutti i controlli sono passati
        FPersistentManager::getInstance()->create($review);
    }

    /**
     * Elimina una recensione esistente.
     * 
     * @param int $idReview: review's ID taken by the review list user see actually
     */
    public function deleteReview(int $idReview) {
        if(!CUser::isLogged()) {
            header ("homepage");
        }
        FPersistentManager::getInstance()->delete($idReview, FReview::class);
        header('Location: Pagina Review Utente');
    }

    /**
     * Modifica una recensione 
     */
    public function editReview($idReview) {
        //Verifico per sicurezza che l'utente sia loggato
        if(!CUser::isLogged()) {
            header("Location: HomPage");
            exit; //Da cambiare con la vera URL della home page
        }
        //Ottengo l'id dell'utente dalla sessione
        $idUser=USessions::getSessionElement('idUser');
        //Arriva una POST HTTP con i dati, che provvedo ad estrarre per creare la EReview modificata
        $review=new EReview(
            $idUser, 
            $idReview, //Campo nascosto nello script HTML o come parametro della funzione
            UHTTPMethods::post('stars'),
            UHTTPMethods::post('body'),
            new DateTime(), //Non deve essere modificabile
            UHTTPMethods::post('idReply') //Campo nascosto nello script HTML
        );
        //Faccio alcuni controlli sui campi inseriti
        if(empty($review->getStars())) {
            throw new Exception("Stars field can't be empty");
        }
        if (empty($review->getBody())) {
            throw new Exception("Body field can't be empty");
        }
        //Aggiorno la review su db se tutti i check sono stati superati
        FPersistentManager::getInstance()->update($review);
        header("Location: Pagina delle rview utente");
    }

}