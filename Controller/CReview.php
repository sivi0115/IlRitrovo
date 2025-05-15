<?php

namespace Controller;

use DateTime;
use Entity\EReview;
use Exception;
use Foundation\FReview;

class CReview
{
    /**
     * Crea una nuova recensione.
     *
     * @return void
     * @throws Exception
     */
    public function creaReview(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new CUser)->isLogged()) {
                try {
                    $utente = unserialize($_SESSION['utente']);

                    $body = $_POST['body'];
                    $creationTime = new DateTime();
                    $stars = $_POST['stars'];
                    $idUser = $utente->getIdUser();
                    $review = new EReview(
                        null,
                        $body,
                        $creationTime,
                        false, // false di default
                        $stars,
                        "", // vuota di default perché ancora non risposta
                        $idUser,
                    );

                    FReview::storeReview($review);
                    header('Location: /location/' . $idLocation . '?success=review_creata');
                    exit();
                } catch (Exception $e) {
                    header('Location: /location/' . $idLocation . '?error=review_non_creata');
                    exit();
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }

    /**
     * Visualizza i dettagli di una recensione.
     *
     * @param int $id L'ID della recensione.
     * @return EReview|null La recensione se trovata, altrimenti null.
     * @throws Exception Se si verifica un errore durante il caricamento della recensione.
     */
    public function visualizzaReview(int $id): ?EReview
    {
        try {
            return FReview::loadReview($id);
        } catch (Exception $e) {
            // Gestisci l'eccezione (es. logging)
            return null;
        }
    }

    /**
     * Aggiorna una recensione esistente.
     *
     * @param int $id L'ID della recensione da aggiornare.
     * @throws Exception Se si verifica un errore durante l'aggiornamento della recensione.
     */
    public function updateReview(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new CUser)->isLogged()) {
                try {
                    $utente = unserialize($_SESSION['utente']);
                    $body = $_POST['body'];
                    $stars = $_POST['stars'];
                    $idUser = $utente->getIdUser();
                    $idLocation = $_POST['idLocation'];

                    $review = new EReview(
                        $id,
                        $body,
                        new DateTime(),
                        false,
                        $stars,
                        "",
                        $idUser,
                        $idLocation
                    );

                    FReview::updateReview($review);
                    header('Location: /user/profile?success=review_aggiornata');
                    exit();
                } catch (Exception $e) {
                    header('Location: /user/profile?error=review_non_aggiornata');
                    exit();
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }

    /**
     * Elimina una recensione esistente.
     *
     * @param int $id L'ID della recensione da eliminare.
     * @throws Exception Se si verifica un errore durante l'eliminazione della recensione.
     */
    public function eliminaReview(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new CUser)->isLogged()) {
                try {
                    FReview::deleteReview($id);
                    header('Location: /user/profile?success=review_eliminata');
                    exit();
                } catch (Exception $e) {
                    header('Location: /user/profile?error=review_non_eliminata');
                    exit();
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }

    /**
     * Visualizza tutte le recensioni di un utente.
     *
     * @param int $idUser L'ID dell'utente di cui visualizzare le recensioni.
     * @return array Un array di oggetti EReview.
     * @throws Exception Se si verifica un errore durante il recupero delle recensioni.
     */
    public function getReviewsByUser(int $idUser): array
    {
        try {
            return FReview::loadReviewByUserId($idUser);
        } catch (Exception $e) {
            throw new Exception("Errore durante il recupero delle recensioni: " . $e->getMessage());
        }
    }

    /**
     * Visualizza tutte le recensioni per una specifica location.
     *
     * @param int $idLocation L'ID della location di cui visualizzare le recensioni.
     * @return array Un array di oggetti EReview.
     * @throws Exception Se si verifica un errore durante il recupero delle recensioni.
     */
    public function getReviewsByLocation(int $idLocation): array
    {
        try {
            return FReview::loadReviewByLocation($idLocation);
        } catch (Exception $e) {
            throw new Exception("Errore durante il recupero delle recensioni: " . $e->getMessage());
        }
    }

    /**
     * Gestisce la ricerca di recensioni per ID.
     *
     * @return EReview|null La recensione se trovata, altrimenti null.
     * @throws Exception Se si verifica un errore durante la ricerca della recensione.
     */
    public function cercaReviews(): ?EReview
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $idReview = $_POST['idReview'];
                if (!is_numeric($idReview)) {
                    throw new Exception("ID recensione non valido.");
                }
                return FReview::loadReview($idReview);
            } catch (Exception $e) {
                throw new Exception("Errore durante la ricerca della recensione: " . $e->getMessage());
            }
        }
        return null;
    }
}