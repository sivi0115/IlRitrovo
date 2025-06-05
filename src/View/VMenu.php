<?php

class VMenu {
    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    public function showMenuPage() {
        // Qui non serve assegnare variabili, il tpl è statico
        $this->smarty->display(dirname(__DIR__) . "/Smarty/templates/menu.tpl");
    }
}
