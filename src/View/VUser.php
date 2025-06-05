<?php


namespace View;
use Smarty\Smarty;

class VUser {

    public function showProfile($username, $email, $name, $username, $birthDate, $phone, $edit_section) {
        $smarty = new Smarty();

        // Assegna variabili Smarty
        $smarty->assign('username', $username->getUsername());
        $smarty->assign('email', $email->getEmail());
        $smarty->assign('name', $utente->getName());
        $smarty->assign('surname', $utente->getSurname());
        $smarty->assign('birthdate', $utente->getBirthDate());
        $smarty->assign('phone', $utente->getPhone());
        $smarty->assign('edit_section', $edit_section);

        // Mostra il template
        $smarty->display('user/useProfile.tpl');
    }
}
