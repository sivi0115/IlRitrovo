<?php

namespace Controller;

class CFrontController{
    
    //punto di accesso principale dell'applicazione
    //fa il parsing della URL per capire quali metodi controller chiamare e con quali parametri
    // le url sui button di ogni template devono esserestrutturate in questo modo /~momok/Class/method
    public function run($richiestaUrl){
    
        //le URL devono essere organizzate in questo modo: /ilRitrovo/public/ControlClass/methodClass    
        

        $richiestaUrl = trim($richiestaUrl, '/');
        $partiUrl = explode('/', $richiestaUrl);
        array_shift($partiUrl);
        array_shift($partiUrl);
/*
        array_shift($partiUrl);
        array_shift($partiUrl);
        array_shift($partiUrl);
        array_shift($partiUrl);
  */     
        //estrazione classe di controllo
       
        if(!empty($partiUrl[0])){ $controller = ucfirst($partiUrl[0]);}
        else{$controller = "User";}
        
        //estrazione metodo di controllo
        if(!empty($partiUrl[1])){$metodo = $partiUrl[1];}
        else{$metodo = "showHomePage";}

        $controller = 'C' . $controller;
        $controllerFile = __DIR__ . "/{$controller}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            // Check if the method exists in the controller
            if (method_exists($controller, $metodo)) {
                $parametri = array_slice($partiUrl, 2); 
                call_user_func_array([$controller, $metodo], $parametri);
            } else {
                // show 404 page
                echo "metodo non esiste";
                header("PERCORSO DA DEFINIRE");
            }
        } else {
            // show 404 page
            echo "classe non esiste";
            header("PERCORSO DA DEFINIRE");
        }
    }
}
