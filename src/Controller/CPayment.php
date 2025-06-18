<?php

namespace Controller;

use DateTime;
use Entity\ECreditCard;
use Entity\EPayment;
use Exception;
use Foundation\FCreditCard;
use Foundation\FPayment;

class CPayment
{
     public function pay() {
        session_start();

        // Dati della prenotazione
        $reservationData = $_SESSION['reservationData'];
        $amount = $reservationData['totalPrice'] * 100; // in centesimi

        // Stripe secret key
        \Stripe\Stripe::setApiKey('sk_test_LA_TUA_SECRET_KEY');

        // Crea PaymentIntent
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
        ]);

        // Passa i dati a Smarty
        $smarty = new Smarty();
        $smarty->assign('reservationData', $reservationData);
        $smarty->assign('clientSecret', $intent->client_secret);
        $smarty->assign('publicKey', 'pk_test_LA_TUA_PUBLIC_KEY');
        $smarty->display('payment.tpl');
    }

    public function success() {
        // Salva prenotazione nel DB
        // ...
        header('Location: CFrontController.php?controller=CReservation&task=confirmation');
    }

}