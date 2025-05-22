<?php
require_once 'EUser.php';
require_once 'EReservation.php';
require_once 'ECreditCard.php';
use Entity\EUser;
use Entity\EReservation;
use Entity\ECreditCard;
use Entity\Role;
use Entity\TimeFrame;

$E1=new EUser(1,
            1,
            'silvietta',
            'silvia@gmail.com',
            'Silvietta123!',
            'https://esempio.it/immagini/abcd.png', 
            'Silvia',
            'Di G',
            new DateTime('15-01-2001'),
            '+393408993462', Role::UTENTE);

$R1= new EReservation(1,
                    1,
                    null,
                    1,
                    new DateTime('15-01-2026'),
                    new DateTime('14-01-2026'),
                    TimeFrame::CENA,
                    'approved',
                    40,
                    5,
                    'se non funziona mi sparo');

$C1=new ECreditCard(1,
                    'Silvia Di G',
                    '1010202030304040',
                    123,
                    new DateTime('31-12-2026'),
                    'Visa',
                    1);

$reservations = [101, 102, 103, 104, 105];

$cards = [1, 2];

print($E1->getIdUser()."\n");
print($E1->getIdReview()."\n");
print($E1->getUsername()."\n");
print($E1->getEmail()."\n");
print($E1->getPassword()."\n");
print($E1->getImage()."\n");
print($E1->getBan()."\n");
print($E1->getName()."\n");
print($E1->getSurname()."\n");
print($E1->getBirthDate()."\n");
print($E1->getPhone()."\n");
print($E1->getRole(). "\n");
print ($E1->isPasswordChanged()."\n");
print_r($E1->getReservations());
print('\n');
print_r($E1->getCreditCards());
print('\n');
print("isUser: " . ($E1->isUser() ? "true" : "false") . "\n");
print("isAdmin: " . ($E1->isAdmin() ? "true" : "false") . "\n");

$E1->setIdUser(2);
$E1->setIdReview(2);
$E1->setUsername('Marcolino');
$E1->setEmail('marco@gmail.com');
$E1->setPassword('Marcolino123!');
$E1->setImage('https://esempio.it/immagini/abcd.png');
$E1->setBan(true);
$E1->setName('Marco');
$E1->setSurname('Cip');
$E1->setBirthDate(new DateTime('12-02-2000'));
$E1->setPhone('+393408993461');
$E1->setRole(Role::AMMINISTRATORE);
$E1->addReservation($R1);
$E1->addCreditCard($C1);

print($E1->getIdUser()."\n");
print($E1->getIdReview()."\n");
print($E1->getUsername()."\n");
print($E1->getEmail()."\n");
print($E1->getPassword()."\n");
print($E1->getImage()."\n");
print($E1->getBan()."\n");
print($E1->getName()."\n");
print($E1->getSurname()."\n");
print($E1->getBirthDate()."\n");
print($E1->getPhone()."\n");
print($E1->getRole(). "\n");
print ($E1->isPasswordChanged()."\n");
print_r($E1->getReservations());
print('\n');
print_r($E1->getCreditCards());
print('\n');
print("isUser: " . ($E1->isUser() ? "true" : "false") . "\n");
print("isAdmin: " . ($E1->isAdmin() ? "true" : "false") . "\n");

print("canWriteReview: " . ($E1->canWriteReview() ? "true" : "false") . "\n");
print("hasReview: " . ($E1->hasReview() ? "true" : "false") . "\n");
print("isPasswordChanged: " . ($E1->isPasswordChanged() ? "true" : "false") . "\n");

$E1->addReservation($R1);
print("hasReservation (after add): " . ($E1->hasReservation() ? "true" : "false") . "\n");
$E1->removeReservation($R1);
print("hasReservation (after remove): " . ($E1->hasReservation() ? "true" : "false") . "\n");

$E1->addCreditCard($C1);
print("hasCreditCard (after add): " . ($E1->hasCreditCard() ? "true" : "false") . "\n");
$E1->removeCreditCard($C1);
print("hasCreditCard (after remove): " . ($E1->hasCreditCard() ? "true" : "false") . "\n");

print_r($E1->jsonSerialize());