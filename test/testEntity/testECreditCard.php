<?php
require_once 'ECreditCard.php';
use Entity\ECreditCard;

$E1=new ECreditCard(1,
                    'Silvia Di G',
                    '1010202030304040',
                    123,
                    new DateTime('31-12-2026'),
                    'Visa',
                    1 );
print($E1->getIdCreditCard()."\n");
print($E1->getIdUser()."\n");
print($E1->getNumber()."\n");
print($E1->getExpiration()."\n");
print($E1->getCvv()."\n");
print($E1->getType()."\n");
print($E1->getHolder()."\n");

$E1->setIdCreditCard(2);
$E1->setIdUser(2);
$E1->setNumber(1111222233334444);
$E1->setExpiration(new DateTime('31-10-2027'));
$E1->setCvv(456);
$E1->setType('Mastercard');
$E1->setHolder('Marco C');

print($E1->getIdCreditCard()."\n");
print($E1->getIdUser()."\n");
print($E1->getNumber()."\n");
print($E1->getExpiration()."\n");
print($E1->getCvv()."\n");
print($E1->getType()."\n");
print($E1->getHolder()."\n");

//TEST PER isValidCardNumber
$refMethod = new ReflectionMethod('Entity\ECreditCard', 'isValidCardNumber');
$refMethod->setAccessible(true);
echo "Test card 1: ";
echo $refMethod->invoke($E1, '4111111111111111') ? "Valid\n" : "Invalid\n";
echo "Test card 2: ";
echo $refMethod->invoke($E1, 'abcd123') ? "Valid\n" : "Invalid\n";

print_r($E1->jsonSerialize());