<?php
namespace Controller;

use Entity\ELocation;
use Exception;
use Foundation\FAssistanceTicket;
use Foundation\FLocation;
use Foundation\FReservation;
use Foundation\FReview;
use Foundation\FUser;
use View\VAdmin;
use View\VOwner;

/**
 * Classe COwner
 *
 * Gestisce le azioni relative al proprietario
 */
class COwner
{
    /**
     * Verifica se l'utente è loggato e se è il proprietario di almeno una location.
     *
     * @return bool True se l'utente è loggato ed è un proprietario, false altrimenti.
     */
    public function isLogged(): bool
    {
        if (isset($_SESSION['utente'])) {
            $utente = unserialize($_SESSION['utente']);
            $idOwner = $utente->getIdOwner(); //questo metodo dovrebbe essere nelle entity?
            try {
                $locations = FLocation::getLocationsByOwner($idOwner);
                return count($locations) > 0;
            } catch (Exception) {
                return false;
            }
        }
        return false;
    }
    /**
     * Mostra la dashboard dell'amministratore con informazioni sulle location in attesa di
     * validazione, gli utenti bloccati e le recensioni.
     */
    public function dashboard(): void
    {
        $fLocation = new FLocation();
        $fUser = new FUser();
        $fReview = new FReview();
        $fAssistanceTicket = new FAssistanceTicket();
        $view = new VAdmin();

        try {
            $pendingLocations = $fLocation->getPendingLocations();
            $blockedUsers = $fUser->getBlockedUsers();
            $reviews = $fReview->loadAllReview();
            $supportMessages = $fAssistanceTicket->loadAllTicket();

            $view->showDashboard($pendingLocations, $blockedUsers, $reviews, $supportMessages);
        } catch (Exception $e) {
            $view->showDashboardError("Errore: " . $e->getMessage());
        }
    }

    /**
     * Approva una prenotazione.
     *
     * @param int $idReservation L'ID della prenotazione da approvare.
     */
    public function approvaPrenotazione(int $idReservation): void
    {
        $view = new VOwner();

        try {
            FReservation::approveReservation($idReservation);
            $view->showSuccess("Prenotazione approvata con successo.");
        } catch (Exception $e) {
            $view->showError("Errore durante l'approvazione della prenotazione: " . $e->getMessage());
        }
    }

    /**
     * Risponde a una recensione.
     *
     * @param int $idReview L'ID della recensione a cui rispondere.
     */
    public function replyToReview(int $idReview): void
    {
        $view = new VOwner(); // Assumo tu abbia una view per l owner
        $fReview = new FReview(); // Assumo tu abbia una classe FReview

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $fReview->replyToReview($idReview, $_POST['reply']);
                $view->showDashboardSuccess("Risposta alla recensione inviata con successo.");
            } catch (Exception $e) {
                $view->showDashboardError("Errore durante l'invio della risposta: " . $e->getMessage());
            }
        } else {
            try {
                // Recupera la recensione per visualizzarla nel form
                $review = $fReview->getReviewById($idReview);
                $view->showReplyReviewForm($review);
            } catch (Exception $e) {
                $view->showDashboardError("Errore: " . $e->getMessage());
            }
        }
    }

    /**
     * Elimina una recensione.
     *
     * @param int $idReview L'ID della recensione da eliminare.
     */
    public function deleteReview(int $idReview): void
    {
        $fReview = new FReview();
        $view = new VAdmin();

        try {
            $fReview->deleteReview($idReview);
            $view->showDashboardSuccess("Recensione eliminata con successo.");
        } catch (Exception $e) {
            $view->showDashboardError("Errore durante l'eliminazione della recensione: " . $e->getMessage());
        }
    }

    /**
     * Banna un utente.
     *
     * @param int $idUser L'ID dell'utente da bannare.
     */
    public function banUser(int $idUser): void
    {
        $view = new VAdmin();
        $fUser = new FUser();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $fUser->banUser($idUser, $_POST['motivation']);
                $view->showDashboardSuccess("Utente bannato con successo.");
            } catch (Exception $e) {
                $view->showDashboardError("Errore durante il ban dell'utente: " . $e->getMessage());
            }
        } else {
            try {
                $user = $fUser->getUserById($idUser);
                $view->showBanUserForm($user);
            } catch (Exception $e) {
                $view->showDashboardError("Errore: " . $e->getMessage());
            }
        }
    }

    public function creaLocation(): void
    {
        $view = new VOwner();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (COwner::isLogged()) {
                try {
                    $utente = unserialize($_SESSION['utente']);

                    $name = $_POST['name'];
                    $vatNumber = $_POST['vatNumber'];
                    $description = $_POST['description'];
                    $photo = $_POST['photo'];
                    $type = $_POST['type'];
                    $idOwner = $utente->getIdOwner();
                    $idAddress = $_POST['idAddress'];

                    $location = new ELocation(
                        null,
                        $name,
                        $vatNumber,
                        $description,
                        $photo,
                        $type,
                        $idOwner,
                        $idAddress
                    );

                    $locationId = FLocation::storeLocation($location);
                    $view->showSuccess("Location creata con successo. ID: " . $locationId);
                } catch (Exception $e) {
                    $view->showError("Errore durante la creazione della location: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        } else {
            $view->showCreateLocationForm();
        }
    }

    public function modificaLocation(int $id): void
    {
        $view = new VOwner();
        $utente = unserialize($_SESSION['utente']);
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (COwner::isLogged()) {
                try {
                    $name = $_POST['name'];
                    $vatNumber = $_POST['vatNumber'];
                    $description = $_POST['description'];
                    $photo = $_POST['photo'];
                    $type = $_POST['type'];
                    $idAddress = $_POST['idAddress'];
                    $existingLocation = FLocation::load($id);
                    if ($existingLocation->getIdOwner() != $utente->getIdOwner()) {
                        throw new Exception("Non sei autorizzato a modificare questa location.");
                    }
                    $location = new ELocation(
                        $id,
                        $name,
                        $vatNumber,
                        $description,
                        $photo,
                        $type,
                        $utente->getIdOwner(),
                        $idAddress
                    );
                    FLocation::updateLocation($location);
                    $view->showSuccess("Location aggiornata con successo.");
                } catch (Exception $e) {
                    $view->showError("Errore durante l'aggiornamento della location: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        } else {
            try {
                $location = FLocation::load($id);
                if ($location->getIdOwner() != $utente->getIdOwner()) {
                    throw new Exception("Non sei autorizzato a visualizzare questa location.");
                }

                $view->showEditLocationForm($location);
            } catch (Exception $e) {
                $view->showError("Errore: " . $e->getMessage());
            }
        }
    }

    public function eliminaLocation(int $id): void
    {
        $view = new VOwner();
        $utente = unserialize($_SESSION['utente']);
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (COwner::isLogged()) {
                try {
                    $existingLocation = FLocation::load($id);
                    if ($existingLocation->getIdOwner() != $utente->getIdOwner()) {
                        throw new Exception("Non sei autorizzato a eliminare questa location.");
                    }
                    FLocation::deleteLocation($id);
                    $view->showSuccess("Location eliminata con successo.");
                } catch (Exception $e) {
                    $view->showError("Errore durante l'eliminazione della location: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }


}