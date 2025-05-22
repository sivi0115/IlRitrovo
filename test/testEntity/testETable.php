<?php
require_once 'ETable.php';
use Entity\ETable;

$E1=new ETable(1, 'spazio', 15);
print($E1->getIdTable()."\n");
print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");

$E1->setIdTable(2);
$E1->setName('boh');
$E1->setMaxGuests(10);

print($E1->getIdTable()."\n");
print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");

print_r($E1->jsonSerialize());