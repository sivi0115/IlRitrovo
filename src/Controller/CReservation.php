<?php

namespace Controller;

use DateTime;
use Entity\EReservation;
use Exception;
use Foundation\FReservation;
use View\VReservation;


class CReservation
{
    /**
     * Crea una nuova prenotazione.
     */
    public function creaPrenotazione(): void
    {
        $view = new VReservation();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new CUser)->isLogged()) {
                try {
                    $utente = unserialize($_SESSION['utente']);

                    $creationTime = new DateTime();
                    $state = $_POST['state'];
                    $comment = $_POST['comment'];
                    $durationEvent = $_POST['durationEvent'];
                    $totPrice = $_POST['totPrice'];
                    $idUser = $utente->getIdUser();
                    $idRoom = $_POST['idRoom'];
                    $idEvent = $_POST['idEvent'];

                    $reservation = new EReservation(
                        null,
                        $creationTime,
                        $state,
                        $comment,
                        $durationEvent,
                        $totPrice,
                        $idUser,
                        $idRoom,
                        $idEvent
                    );

                    $reservationId = FReservation::storeReservation($reservation);
                    $view->showSuccess("Prenotazione creata con successo. ID: " . $reservationId);
                } catch (Exception $e) {
                    $view->showError("Errore durante la creazione della prenotazione: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        } else {
            $view->showCreateReservationForm();
        }
    }

    /**
     * Visualizza i dettagli di una prenotazione.
     *
     * @param int $id L'ID della prenotazione.
     */
    public function visualizzaPrenotazione(int $id): void
    {
        $view = new VReservation();

        try {
            $reservation = FReservation::loadReservation($id, 'idReservation');
            if ($reservation) {
                $view->showReservationDetailsPage($reservation);
            } else {
                $view->showError("Prenotazione non trovata.");
            }
        } catch (Exception $e) {
            $view->showError("Errore: " . $e->getMessage());
        }
    }

    /**
     * Aggiorna una prenotazione esistente.
     *
     * @param int $id L'ID della prenotazione da aggiornare.
     */
    public function updateReservation(int $id): void
    {
        $view = new VReservation();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new CUser)->isLogged()) {
                try {
                    $utente = unserialize($_SESSION['utente']);

                    $creationTime = new DateTime($_POST['creationTime']);
                    $state = $_POST['state'];
                    $comment = $_POST['comment'];
                    $durationEvent = $_POST['durationEvent'];
                    $totPrice = $_POST['totPrice'];
                    $idUser = $utente->getIdUser();
                    $idRoom = $_POST['idRoom'];
                    $idEvent = $_POST['idEvent'];

                    $reservation = new EReservation(
                        $id,
                        $creationTime,
                        $state,
                        $comment,
                        $durationEvent,
                        $totPrice,
                        $idUser,
                        $idRoom,
                        $idEvent
                    );

                    FReservation::updateReservation($reservation);
                    $view->showSuccess("Prenotazione aggiornata con successo.");
                } catch (Exception $e) {
                    $view->showError("Errore durante l'aggiornamento della prenotazione: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        } else {
            try {
                $reservation = FReservation::loadReservation($id, 'idReservation');
                $view->showEditReservationForm($reservation);
            } catch (Exception $e) {
                $view->showError("Errore: " . $e->getMessage());
            }
        }
    }

    /**
     * Elimina una prenotazione esistente.
     *
     * @param int $id L'ID della prenotazione da eliminare.
     */
    public function eliminaPrenotazione(int $id): void
    {
        $view = new VReservation();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new CUser)->isLogged()) {
                try {
                    FReservation::deleteReservation($id);
                    $view->showSuccess("Prenotazione eliminata con successo.");
                } catch (Exception $e) {
                    $view->showError("Errore durante l'eliminazione della prenotazione: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }

    /**
     * Approva una prenotazione.
     *
     * @param int $idReservation L'ID della prenotazione da approvare.
     */
    public function approvaPrenotazione(int $idReservation): void
    {
        $view = new VReservation();

        try {
            FReservation::approveReservation($idReservation);
            $view->showSuccess("Prenotazione approvata con successo.");
        } catch (Exception $e) {
            $view->showError("Errore durante l'approvazione della prenotazione: " . $e->getMessage());
        }
    }

    /**
     * Rifiuta una prenotazione.
     *
     * @param int $idReservation L'ID della prenotazione da rifiutare.
     */
    public function rifiutaPrenotazione(int $idReservation): void
    {
        $view = new VReservation();

        try {
            FReservation::rejectReservation($idReservation);
            $view->showSuccess("Prenotazione rifiutata con successo.");
        } catch (Exception $e) {
            $view->showError("Errore durante il rifiuto della prenotazione: " . $e->getMessage());
        }
    }

    /**
     * Rifiuta una prenotazione.
     *
     * @param int $idReservation L'ID della prenotazione da rifiutare.
     */
    public function cancellaPrenotazione(int $idReservation): void
    {
        $view = new VReservation();

        try {
            FReservation::cancelReservation($idReservation);
            $view->showSuccess("Prenotazione cancellata con successo.");
        } catch (Exception $e) {
            $view->showError("Errore durante la cancellazione della prenotazione: " . $e->getMessage());
        }
    }

    /**
     * Visualizza tutte le prenotazioni di un utente.
     *
     * @param int $idUser L'ID dell'utente di cui visualizzare le prenotazioni.
     */
    public function visualizzaPrenotazioniUtente(int $idUser): void
    {
        $view = new VReservation();

        try {
            $reservations = FReservation::getReservationsByUserId($idUser);
            $view->showReservationsPage($reservations);
        } catch (Exception $e) {
            $view->showError("Errore durante il recupero delle prenotazioni: " . $e->getMessage());
        }
    }

    /**
     * Visualizza tutte le prenotazioni per una specifica stanza.
     *
     * @param int $roomId L'ID della stanza di cui visualizzare le prenotazioni.
     */
    public function visualizzaPrenotazioniStanza(int $roomId): void
    {
        $view = new VReservation();

        try {
            $reservations = FReservation::getReservationsByRoom($roomId);
            $view->showReservationsPage($reservations);
        } catch (Exception $e) {
            $view->showError("Errore durante il recupero delle prenotazioni: " . $e->getMessage());
        }
    }

    /**
     * Gestisce la ricerca di prenotazioni per ID.
     */
    public function cercaPrenotazioni(): void
    {
        $view = new VReservation();
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $idReservation = $_POST['idReservation'];
                if (!is_numeric($idReservation)) {
                    throw new Exception("ID prenotazione non valido.");
                }
                $prenotazione = FReservation::loadReservation($idReservation, 'idReservation');
                if (!$prenotazione) {
                    $view->showError("Prenotazione non trovata.");
                } else {
                    $view->showReservationDetailsPage($prenotazione);
                }
            } catch (Exception $e) {
                $view->showError("Errore durante la ricerca della prenotazione: " . $e->getMessage());
            }
        }
    }
}