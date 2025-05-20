<?php
namespace Controller;

use Exception;
use Foundation\FAssistanceTicket;
use Foundation\FCreditCard;
use Foundation\FLocation;
use Foundation\FOwner;
use Foundation\FReview;
use Foundation\FRoom;
use Foundation\FService;
use Foundation\FUser;
use View\VAdmin;


/**
 * Classe CAdmin
 *
 * Gestisce le azioni relative all'amministratore, come la validazione dei proprietari,
 * la gestione dei ticket di assistenza e la moderazione delle recensioni.
 */
class CAdmin
{
    /**
     * Verifica se l'utente è loggato e se è un amministratore.
     *
     * @return bool True se l'utente è loggato ed è un amministratore, false altrimenti.
     */
    private function isLogged(): bool
    {
        if (isset($_SESSION['utente'])) {
            $utente = unserialize($_SESSION['utente']);
            return $utente->isAdmin();
        }
        return false;
    }

    public function dashboard(): void
    {
        if ($this->isLogged()) {
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
        } else {
            header('Location: /EventHubWEB/login');
            exit();
        }
    }

    /**
     * Valida un proprietario di una location.
     *
     * @param int $idOwner L'ID del proprietario da validare.
     */
    public function validateOwner(int $idOwner): void
    {
        if ($this->isLogged()) {
            $fOwner = new FOwner();
            $view = new VAdmin();

            try {
                $fOwner->validateOwner($idOwner);
                $view->showDashboardSuccess("Proprietario validato con successo.");
            } catch (Exception $e) {
                $view->showDashboardError("Errore durante la validazione del proprietario: " . $e->getMessage());
            }
        } else {
            header('Location: /EventHubWEB/login');
            exit();
        }
    }

    /**
     * Risponde a un ticket di assistenza.
     *
     * @param int $idTicket L'ID del ticket a cui rispondere.
     */
    public function replyToTicket(int $idTicket): void
    {
        if ($this->isLogged()) {
            $view = new VAdmin();

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $fAssistanceTicket = new FAssistanceTicket();
                try {
                    $ticket = $fAssistanceTicket->loadTicket($idTicket);
                    if ($ticket instanceof \Entity\EAssistanceTicket) {
                        $fAssistanceTicket->replyToTicket($ticket->getIdTicket(), $_POST['reply']);
                        $view->showDashboardSuccess("Risposta al ticket inviata con successo.");
                    } else {
                        $view->showDashboardError("Ticket non trovato.");
                    }
                } catch (Exception $e) {
                    $view->showDashboardError("Errore durante l'invio della risposta: " . $e->getMessage());
                }
            } else {
                $view->showReplyTicketForm($idTicket);
            }
        } else {
            header('Location: /EventHubWEB/login');
            exit();
        }
    }



    /**
     * Elimina una recensione.
     *
     * @param int $idReview L'ID della recensione da eliminare.
     */
    public function deleteReview(int $idReview): void
    {
        if ($this->isLogged()) {
            $fReview = new FReview();
            $view = new VAdmin();

            try {
                $fReview->deleteReview($idReview);
                $view->showDashboardSuccess("Recensione eliminata con successo.");
            } catch (Exception $e) {
                $view->showDashboardError("Errore durante l'eliminazione della recensione: " . $e->getMessage());
            }
        } else {
            // Reindirizza alla pagina di login o mostra un messaggio di errore
            header('Location: /EventHubWEB/login');
            exit();
        }
    }

    /**
     * Banna un utente.
     *
     * @param int $idUser L'ID dell'utente da bannare.
     * @throws Exception
     */
    public function banUser(int $idUser): void
    {
        if ($this->isLogged()) {
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
                $user = $fUser->getUserById($idUser);
                $view->showBanUserForm($user);
            }
        } else {
            header('Location: /EventHubWEB/login');
            exit();
        }
    }

    /**
     * Mostra la pagina con la lista delle location.
     */
    public function showLocations(): void
    {
        if ($this->isLogged()) {
            $view = new VAdmin();
            try {
                $locations = FLocation::loadAllLocations();
                $pendingLocations = FLocation::getPendingLocations();
                $view->showLocations($locations, $pendingLocations);
            } catch (Exception $e) {
                $view->showError("Errore durante il caricamento delle location: " . $e->getMessage());
            }
        } else {
            // Reindirizza alla pagina di login o mostra un messaggio di errore
            header('Location: /EventHubWEB/login');
            exit();
        }
    }

    /**
     * Mostra il profilo di una location.
     *
     * @param int $idLocation L'ID della location.
     */
    public function showLocationProfile(int $idLocation): void
    {
        if ($this->isLogged()) {
            $view = new VAdmin();
            try {
                $location = FLocation::load($idLocation);
                $rooms = FRoom::getRoomsByLocation($idLocation);
                $services = FService::getServicesByLocation($idLocation);
                $view->showLocationProfile($location, $rooms, $services);
            } catch (Exception $e) {
                $view->showError("Errore durante il caricamento del profilo della location: " . $e->getMessage());
            }
        } else {
            // Reindirizza alla pagina di login o mostra un messaggio di errore
            header('Location: /EventHubWEB/login');
            exit();
        }
    }
    /**
     * Banna una recensione.
     *
     * @param int $idReview L'ID della recensione da bannare.
     */
    public function banReview(int $idReview): void
    {
        if ($this->isLogged()) {
            $view = new VAdmin();
            try {
                FReview::banReview($idReview);
                $view->showDashboardSuccess("Recensione bannata con successo.");
            } catch (Exception $e) {
                $view->showDashboardError("Errore durante il ban della recensione: " . $e->getMessage());
            }
        } else {
            header('Location: /EventHubWEB/login');
            exit();
        }
    }

    /**
     * Mostra le recensioni di una location.
     *
     * @param int $idLocation L'ID della location.
     */
    public function showLocationReviews(int $idLocation): void
    {
        if ($this->isLogged()) {
            $view = new VAdmin();
            try {
                $reviews = FReview::loadReviewByLocation($idLocation);
                $view->showLocationReviews($reviews);
            } catch (Exception $e) {
                $view->showError("Errore durante il caricamento delle recensioni della location: " . $e->getMessage());
            }
        } else {
            header('Location: /EventHubWEB/login');
            exit();
        }
    }

    /**
     * Mostra il profilo di un utente.
     *
     * @param int $idUser L'ID dell'utente.
     */
    public function showUserProfile(int $idUser): void
    {
        if ($this->isLogged()) {
            $view = new VAdmin();
            try {
                $user = (new \Foundation\FUser)->getUserById($idUser);
                $cards = FCreditCard::loadCreditCardByUser($idUser);
                $reviews = FReview::loadReviewByUserId($idUser);
                $view->showUserProfile($user, $cards, $reviews);
            } catch (Exception $e) {
                $view->showError("Errore durante il caricamento del profilo utente: " . $e->getMessage());
            }
        } else {
            // Reindirizza alla pagina di login o mostra un messaggio di errore
            header('Location: /EventHubWEB/login');
            exit();
        }
    }
}