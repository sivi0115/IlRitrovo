<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Smarty\Smarty;

class CFrontController
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(__DIR__ . '/../templates/');
        $this->smarty->setCompileDir(__DIR__ . '/../templates_c/');
    }

    public function dispatch(): void
    {
        $controllerName = $_GET['controller'] ?? null;
        $taskName = $_GET['task'] ?? null;

        // Special case: se controller è "front" usa i metodi locali
        if ($controllerName === 'front' && method_exists($this, $taskName)) {
            $this->$taskName();
            return;
        }

        // Mappa dei controller
        $controllerMap = [
            'CUser' => Controller\CUser::class,
            'CCreditCard' => Controller\CCreditCard::class,
            'CExtra' => Controller\CExtra::class,
            'CPayment' => Controller\CPayment::class,
            'CReply' => Controller\CReply::class,
            'CReservation' => Controller\CReservation::class,
            'CReview' => Controller\CReview::class,
        ];

        try {
            if (!$controllerName || !$taskName) {
                throw new Exception("Controller o task non specificato.");
            }

            if (!isset($controllerMap[$controllerName])) {
                throw new Exception("Controller '$controllerName' non trovato.");
            }

            $controllerClass = $controllerMap[$controllerName];
            $controller = new $controllerClass();

            if (!method_exists($controller, $taskName)) {
                throw new Exception("Metodo '$taskName' non esiste nel controller '$controllerClass'.");
            }

            $controller->$taskName();
        } catch (Exception $e) {
            $this->smarty->assign('errorMessage', $e->getMessage());
            $this->smarty->display('error.tpl');
        }
    }

    // Metodo per mostrare la home page
    public function showHome()
    {
        $this->smarty->display('home.tpl');
    }

    // Metodo per mostrare la pagina rooms
    public function showRooms()
    {
        $this->smarty->display('rooms.tpl');
    }

    // Metodo per mostrare la pagina menu
    public function showMenu()
    {
        $this->smarty->display('menu.tpl');
    }
}

// Esegui il front controller
$frontController = new CFrontController();
$frontController->dispatch();
