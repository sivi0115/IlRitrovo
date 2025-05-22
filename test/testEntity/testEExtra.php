<?php
require_once 'testEExtra.php';
use Entity\EExtra;

$E1 = new EExtra(1,
                'fiori',
                49.99
);

print($E1->getIdExtra()."\n");
print($E1->getNameExtra()."\n");
print($E1->getPriceExtra()."\n");

$E1->setIdExtra(2);
$E1->setNameExtra('palloncini');
$E1->setPriceExtra(30);

print($E1->getIdExtra()."\n");
print($E1->getNameExtra()."\n");
print($E1->getPriceExtra()."\n");

print_r($E1->jsonSerialize());