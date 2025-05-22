<?php

require_once 'EArea.php';
use Entity\EArea;

$E1=new EArea('spazio', 15);
print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");

$E1->setName('boh');
$E1->setMaxGuests(10);

print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");

print_r($E1->jsonSerialize());