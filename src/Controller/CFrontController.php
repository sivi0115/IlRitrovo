<?php

namespace Controller;

use View\VError;

class CFrontController
{
    public function run()
    {
        // Recupera il percorso della richiesta (escludendo query string)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = trim($uri, '/');

        // Divide il percorso in segmenti
        $resource = explode('/', $path);

        // Determina il controller e il metodo
        $controllerName = isset($resource[0]) && !empty($resource[0])
            ? 'Controller\\C' . ucfirst(strtolower($resource[0]))
            : 'Controller\\CUser'; // Default controller
        $methodName = isset($resource[1]) && !empty($resource[1])
            ? $resource[1]
            : 'index'; // Default metodo

        // Costruisce il percorso del file del controller
        $controllerPath = __DIR__ . "/C" . ucfirst(strtolower($resource[0])) . ".php";

        // Controlla se il file del controller esiste
        if (file_exists($controllerPath)) {
            require_once $controllerPath;

            // Controlla se la classe esiste
            if (class_exists($controllerName)) {
                $controller = new $controllerName();

                // Controlla se il metodo esiste
                if (method_exists($controller, $methodName)) {
                    // Estrae i parametri dal percorso
                    $params = array_slice($resource, 2);

                    // Chiama il metodo con i parametri
                    call_user_func_array([$controller, $methodName], $params);
                    return;
                }
            }
        }

        // Se il controller o il metodo non esistono, gestisce l'errore
        $this->handleError(404, "La pagina richiesta non esiste.");
    }

    private function handleError($code, $message)
    {
        // Imposta il codice di stato HTTP
        http_response_code($code);

        $view = new VError();
        $view->render($message);

        // Termina l'esecuzione
        exit;
    }
}
