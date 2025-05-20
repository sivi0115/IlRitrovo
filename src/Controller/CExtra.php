<?php

namespace Controller;

use Entity\EExtra;
use Exception;
use Foundation\FPersistentManager;
use View\VLocation;
use View\VOwner;

class CExtra
{

    /**
     * Aggiunge un nuovo extra.
     *
     * Reindirizza alla pagina di gestione degli extra con un messaggio di successo o di errore.
     */
    public function aggiungiExtra(): void
    {
        $view = new VOwner();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new COwner)->isLogged()) {
                try {
                    $type = $_POST['type'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
                    $extra = new EExtra(null, $type, $description, $price);
                    FPersistentManager::getInstance()->store($extra);
                    $view->showSuccessInsert("Extra aggiunto con successo.");
                } catch (Exception $e) {
                    $view->showErrorInsert("Errore durante l'aggiunta dell'extra: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }
    /**
     * Rimuove un extra dal sistema.
     *
     * @param int $id L'ID dell'extra da rimuovere.
     */
    public function rimuoviExtra(int $id): void
    {
        $view = new VOwner();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new COwner)->isLogged()) {
                try {
                    FPersistentManager::getInstance()->delete($id, "FExtra");

                    $view->showSuccessRemove("Extra rimosso con successo.");
                } catch (Exception $e) {
                    $view->showErrorRemove("Errore durante la rimozione dell'extra: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }

    /**
     * Aggiorna un extra esistente.
     */
    public function aggiornaExtra(): void
    {
        $view = new VOwner();
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if ((new COwner)->isLogged()) {
                try {
                    $id = $_POST['id'];
                    $type = $_POST['type'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
                    $extra = new EExtra($id, $type, $description, $price);
                    FPersistentManager::getInstance()->update($extra);
                    $view->showSuccessUpdate("Extra aggiornato con successo.");
                } catch (Exception $e) {
                    $view->showError("Errore durante l'aggiornamento dell'extra: " . $e->getMessage());
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }

    /**
     * Mostra i dettagli di un extra specifico.
     *
     * @param int $id L'ID dell'extra da visualizzare.
     */
    public function visualizzaExtra(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if ((new CUser)->isLogged()) {
                try {
                    $view = new VLocation();
                    $pm = FPersistentManager::getInstance();
                    $extra = $pm->load("id", $id, "FExtra");
                    $view->showExtraDetailsPage($extra);
                } catch (Exception $e) {
                    header("Location: /EventHubWEB/error?error=" . urlencode($e->getMessage()));
                    exit();
                }
            } else {
                header('Location: /EventHubWEB/login');
                exit();
            }
        }
    }

    /**
     * Mostra il form per la modifica di un extra esistente.
     *
     * @param int $id L'ID dell'extra da modificare.
     */
    public function modificaExtra(int $id): void
    {
        $view = new VOwner();

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            try {
                $pm = FPersistentManager::getInstance();
                $extra = $pm->load("id", $id, "FExtra");
                $view->showEditExtraForm($extra);
            } catch (Exception $e) {
                $view->showError("Errore durante il caricamento dell'extra: " . $e->getMessage());
            }
        }
    }

    /**
     * Attiva o disattiva un extra.
     *
     * @param int $id L'ID dell'extra.
     * @param int $stato Lo stato da impostare (1 per attivo, 0 per disattivo).
     */
    public function attivaDisattivaExtra(int $id, int $stato): void
    {
        $view = new VOwner();
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $pm = FPersistentManager::getInstance();
                $extra = $pm->load("id", $id, "FExtra");
                if ($extra) {
                    $extra->setActive($stato);
                    $pm->update($extra);
                    $view->showSuccessUpdate("Stato extra aggiornato con successo.");
                } else {
                    $view->showErrorUpdate("Extra non trovato.");
                }
            } catch (Exception $e) {
                $view->showErrorUpdate("Errore durante l'aggiornamento dello stato dell'extra: " . $e->getMessage());
            }
        }
    }
}