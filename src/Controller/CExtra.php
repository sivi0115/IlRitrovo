<?php

namespace Controller;

use Entity\EExtra;
use Foundation\FExtra;
use View\VExtra;
use Foundation\FPersistentManager;
use View\VUser;
use Utility\UCookies;
use Utility\UHTTPMethods;
use Utility\UServer;
use Utility\USessions;


class CExtra {
    /**
     * Function to show extra's page
     */
    public function showExtrasPage() {
        $viewU=new VUser();
        $viewE=new VExtra();
        $session=USessions::getIstance();
        if($isLogged=CUser::isLogged()) {
            $idUser=$session->readValue('idUser');
        }
        //Carico tutti gli extra da db
        $allExtras=FPersistentManager::getInstance()->readAll(FExtra::class);
        $viewU->showAdminHeader($isLogged);
        $viewE->showExtrasPage($allExtras);

    }

}