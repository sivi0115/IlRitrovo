<?php

require_once 'EExtra.php';
use Entity\EExtra;


$E1=new EExtra(1, "fiori", 10);
/**print($E1->getIdExtra());
print($E1->getNameExtra());
print($E1->getPriceExtra());

$E1->setIdExtra(2);
$E1->setNameExtra('musica');
$E1->setPriceExtra(20);

print($E1->getIdExtra());
print($E1->getNameExtra());
print($E1->getPriceExtra());
*/
print_r($E1->jsonSerialize());



