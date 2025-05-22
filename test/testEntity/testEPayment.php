<?php 
require_once 'EPayment.php';
use Entity\EPayment;
use Entity\StatoPagamento;

$E1=new EPayment(1, 
                1,
                1,
                50, 
                new DateTime('31-12-2025'),
                StatoPagamento::IN_ATTESA);
print($E1->getIdPayment()."\n");
print($E1->getIdCreditCard()."\n");
print($E1->getIdReservation()."\n");
print($E1->getTotal()."\n");
print($E1->getCreationTime()."\n");
print($E1->getState()."\n");

$E1->setIdPayment(2);
$E1->setIdCreditCard(2);
$E1->setIdReservation(2);
$E1->setTotal(100);
$E1->setCreationTime(new DateTime('30-10-2025'));
$E1->setState(StatoPagamento::COMPLETATO);

print($E1->getIdPayment()."\n");
print($E1->getIdCreditCard()."\n");
print($E1->getIdReservation()."\n");
print($E1->getTotal()."\n");
print($E1->getCreationTime()."\n");
print($E1->getState()."\n");

print_r($E1->jsonSerialize());