<?php
require_once 'ERoom.php';
use Entity\ERoom;

$E1=new ERoom(1, 'spazio', 15, 50);
print($E1->getIdRoom()."\n");
print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");
print($E1->getTax()."\n");

$E1->setIdRoom(2);
$E1->setName('boh');
$E1->setMaxGuests(10);
$E1->setTax(80);

print($E1->getIdRoom()."\n");
print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");
print($E1->getTax()."\n");

print_r($E1->jsonSerialize());