<?php
/**
 * Ciao Marco, ti introduco a come ho strutturato questo ambiente di testing:
 * ogni classe "testata" ha la sua sezione all'interno del codice perchè avevo paura che dei get o set che si chiamano uguali potrebbero creare casino.
 * Puoi "scommentarli" uno alla volta e testarli.
 * ORDINE DELLE CLASSI:
 * 1. Credit Card
 * 2. Payment
 * 3. Area
 * 4. Table
 * 5. Room
 * 6. User
 * Grazie per la collaborazione e scusa per il disturbo, cercherò di sistemare il pc.
 */

/**require_once 'ECreditCard.php';
use Entity\ECreditCard;

$E1=new ECreditCard(1, 1, 1010202030304040, new DateTime('31-12-2026'), 123, 'Visa', 'Silvia Di G');
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
$E1->setExpiration(new DateTime('31/10/27'));
$E1->setCvv(456);
$E1->setType('MasterCard');
$E1->setHolder('Marco C');

print($E1->getIdCreditCard()."\n");
print($E1->getIdUser()."\n");
print($E1->getNumber()."\n");
print($E1->getExpiration()."\n");
print($E1->getCvv()."\n");
print($E1->getType()."\n");
print($E1->getHolder()."\n");

print_r($E1->jsonSerialize()); */

/**require_once 'EPayment.php';
use Entity\EPayment;
use Entity\StatoPagamento;

$E1=new EPayment(1, 1, 1, 50,  new DateTime('31-12-2025'), StatoPagamento::IN_ATTESA);
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
$E1->setCreationTime(new DateTime('30/10/25'));
$E1->setState(StatoPagamento::COMPLETATO);

print($E1->getIdPayment()."\n");
print($E1->getIdCreditCard()."\n");
print($E1->getIdReservation()."\n");
print($E1->getTotal()."\n");
print($E1->getCreationTime()."\n");
print($E1->getState()."\n");

print_r($E1->jsonSerialize());*/

/**require_once 'EArea.php';
use Entity\EArea;

$E1=new EArea('spazio', 15);
print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");

$E1->setName('boh');
$E1->setMaxGuests(10);

print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");

print_r($E1->jsonSerialize());*/

/**require_once 'ETable.php';
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

print_r($E1->jsonSerialize());*/

/**require_once 'ERoom.php';
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

print_r($E1->jsonSerialize());*/

require_once 'EUser.php';
use Entity\EUser;

$E1=new EUser(1, 1, 'silvietta', 'silvia@gmail.com', 'Silvietta123!', 'abcd.png', false, 'Silvia', 'Di G', new DateTime('15-01-2001'), '123457890');
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
print ($a->isPasswordChanged()."\n");
print_r($b->getReservations()."\n");
print_r($d->getCreditCards()."\n");

$E1->setIdUser(2);
$E1->setIdReview(2);
$E1->setUsername('Marcolino');
$E1->setEmail('marco@gmail.com');
$E1->setPassword('Marcolino123!');
$E1->setImage('efgh.png');
$E1->setBan(true);
$E1->setName('Marco');
$E1->setSurname('Cip');
$E1->setBirthDate(new DateTime('12-02-2000'));
$E1->setPhone('0987654321');
$c->addReservation(100);
$e->addCreditCard(3);

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
print ($a->isPasswordChanged()."\n");
print_r($b->getReservations()."\n");
print_r($e->getCreditCards()."\n");

print_r($E1->jsonSerialize());