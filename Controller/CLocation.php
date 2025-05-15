<?php

namespace Controller;

use Exception;
use Foundation\FLocation;
use Foundation\FReview;
use View\VLocation;

/**
 * Classe CLocation
 * Gestisce le azioni relative alle location.
 */
class CLocation
{
    /**
     * Visualizza i dettagli di una location, incluse le recensioni.
     *
     * @param int $idLocation L'ID della location da visualizzare.
     * @throws Exception Se la location non viene trovata.
     */
    public function locationDetails(int $idLocation): void
    {
        try {
            $location = FLocation::load($idLocation);
            if ($location == null) {
                throw new Exception("Location non trovata");
            }

            $reviews = FReview::loadReviewByLocation($idLocation);

            $view = new VLocation();
            $view->showLocationDetailsPage($location, $reviews);
        } catch (Exception $e) {
            header("Location: /EventhubNewMain/error?error=" . urlencode($e->getMessage()));
            exit();
        }
    }

    /**
     * Visualizza una location.
     *
     * @param int $id L'ID della location da visualizzare.
     */
    public function visualizzaLocation(int $id): void
    {
        $view = new VLocation();

        try {
            $location = FLocation::load($id);
            if ($location) {
                $view->showLocationPage($location);
            } else {
                $view->showError("Location non trovata.");
            }
        } catch (Exception $e) {
            $view->showError("Errore: " . $e->getMessage());
        }
    }

    /**
     * Gestisce la ricerca di location per nome.
     *
     * Il metodo recupera il nome della location da `$_POST['name']` e utilizza
     * `FLocation::searchLocationByName()` per cercare le location corrispondenti.
     * Infine, visualizza i risultati della ricerca tramite la view `VLocation`.
     */
    public function cercaLocation(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $view = new VLocation();
                $name = $_POST['name'];
                $locations = FLocation::searchLocationByName($name);
                $view->showLocationsPage($locations);
            } catch (Exception $e) {
                header("Location: /EventhubNewMain/error?error=" . urlencode($e->getMessage()));
                exit();
            }
        }
    }

    /**
     * Mostra tutte le location di un determinato proprietario.
     *
     * @param int $idOwner L'ID del proprietario di cui visualizzare le location.
     */
    public function showLocationsByOwner(int $idOwner): void
    {
        $view = new VLocation();
        try {
            $locations = FLocation::getLocationsByOwner($idOwner);
            $view->showLocationsPage($locations);
        } catch (Exception $e) {
            $view->showError("Errore durante il caricamento delle location: " . $e->getMessage());
        }
    }

    /**
     * Filtra le location in base ai servizi offerti.
     *
     * @param array $services Un array di ID dei servizi da utilizzare per il filtraggio.
     */
    public function filterByServices(array $services): void
    {
        $view = new VLocation();
        try {
            $locations = FLocation::filterByServices($services);
            $view->showLocationsPage($locations);
        } catch (Exception $e) {
            $view->showError("Errore durante il filtraggio delle location: " . $e->getMessage());
        }
    }

    /**
     * Filtra le location in base agli extra disponibili.
     *
     * @param array $extraIds Un array di ID degli extra da utilizzare per il filtraggio.
     */
    public function filterByExtras(array $extraIds): void
    {
        $view = new VLocation();
        try {
            $locations = FLocation::filterByExtras($extraIds);
            $view->showLocationsPage($locations);
        } catch (Exception $e) {
            $view->showError("Errore durante il filtraggio delle location: " . $e->getMessage());
        }
    }
}