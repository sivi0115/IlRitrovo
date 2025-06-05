<?php


namespace View;


class VUser {

    public function showProfile($utente, $edit_section = null) {
        $smarty = new Smarty();

        // Assegna variabili Smarty
        $smarty->assign('username', $utente->getUsername());
        $smarty->assign('email', $utente->getEmail());
        $smarty->assign('name', $utente->getName());
        $smarty->assign('surname', $utente->getSurname());
        $smarty->assign('birthdate', $utente->getBirthDate());
        $smarty->assign('phone', $utente->getPhone());
        $smarty->assign('edit_section', $edit_section);

        // Mostra il template
        $smarty->display('user/profile.tpl');
    }
}
