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
 * 7. Reservation
 * 8. Review
 * 9. Replay
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

print_r($E1->jsonSerialize());*/

/*require_once 'EPayment.php';
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
$E1->setCreationTime(new DateTime('30-10-2025'));
$E1->setState(StatoPagamento::COMPLETATO);

print($E1->getIdPayment()."\n");
print($E1->getIdCreditCard()."\n");
print($E1->getIdReservation()."\n");
print($E1->getTotal()."\n");
print($E1->getCreationTime()."\n");
print($E1->getState()."\n");

print_r($E1->jsonSerialize());*/

/*require_once 'EArea.php';
use Entity\EArea;

$E1=new EArea('spazio', 15);
print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");

$E1->setName('boh');
$E1->setMaxGuests(10);

print($E1->getareaName()."\n");
print($E1->getMaxGuests()."\n");

print_r($E1->jsonSerialize());*/

/*require_once 'ETable.php';
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

/*require_once 'ERoom.php';
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
require_once 'EReservation.php';
require_once 'ECreditCard.php';
use Entity\EUser;
use Entity\EReservation;
use Entity\ECreditCard;
use Entity\Role;

/**$E1=new EUser(1, 1, 'silvietta', 'silvia@gmail.com', 'Silvietta123!', 'https://esempio.it/immagini/abcd.png', false, 'Silvia', 'Di G', new DateTime('15-01-2001'), '+393408993462', Role::UTENTE);
$R1= new EReservation(1, 1, 1, 1, new DateTime('15-01-2026'), new DateTime('14-01-2026'), 'approved', 1, 4, 'intolleranze');
$C1=new ECreditCard(1, 1, 1010202030304040, new DateTime('31-12-2026'), 123, 'Visa', 'Silvia Di G');
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

print_r($E1->jsonSerialize());*/

/**require_once 'EReservation.php';
use Entity\EReservation;

$E1= new EReservation(1, 1, 1, 1, new DateTime('14-01-2026'), new DateTime('15-01-2026'), 'confirmed', 1, 4, 'intolleranze');

print($E1->getIdReservation()."\n");
print($E1->getIdUser()."\n");
print($E1->getIdTable()."\n");
print($E1->getIdRoom()."\n");
print($E1->getCreationTime()."\n");
print($E1->getReservationDate()."\n");
print($E1->getState()."\n");
print($E1->getTotPrice()."\n");
print($E1->getPeople()."\n");
print($E1->getComment()."\n");

$E1->setIdReservation(2);
$E1->setIdUser(2);
$E1->setIdTable(2);
$E1->setIdRoom(2);
$E1->setCreationTime(new DateTime('11-02-2025'));
$E1->setReservationDate(new DateTime('12-02-2025'));
$E1->setState('approved');
$E1->setTotPrice(2);
$E1->setPeople(5);
$E1->setComment('intolleranze2');

print($E1->getIdReservation()."\n");
print($E1->getIdUser()."\n");
print($E1->getIdTable()."\n");
print($E1->getIdRoom()."\n");
print($E1->getCreationTime()."\n");
print($E1->getReservationDate()."\n");
print($E1->getState()."\n");
print($E1->getTotPrice()."\n");
print($E1->getPeople()."\n");
print($E1->getComment()."\n");

print_r($E1->jsonSerialize());*/

/**require_once 'EReview.php';
use Entity\EReview;

$REV1= new EReview(1, 1, 1, 1, 'Corpo', new DateTime('12-01-2020'), false );
print($REV1->getIdUser()."\n");
print($REV1->getIdReview()."\n");
print($REV1->getIdReply()."\n");
print($REV1->getStars()."\n");
print($REV1->getBody()."\n");
print($REV1->getCreationTime()."\n");
print($REV1->getRemoved()."\n");

$REV1->setIdUser(2);
$REV1->setIdReview(2);
$REV1->setIdReply(2);
$REV1->setStars(2);
$REV1->setBody('corpo2');
$REV1->setCreationTime(new DateTime('12-02-2025'));
$REV1->setRemoved(true);

print($REV1->getIdUser()."\n");
print($REV1->getIdReview()."\n");
print($REV1->getIdReply()."\n");
print($REV1->getStars()."\n");
print($REV1->getBody()."\n");
print($REV1->getCreationTime()."\n");
print($REV1->getRemoved()."\n");

print_r($REV1->jsonSerialize());*/

/**require_once 'EReply.php';
require_once 'EReview.php';
use Entity\EReply;

$REP1=new EReply(1, new Datetime('13-01-2021'), 'Corpo');

print($REP1->getIdReply()."\n");
print($REP1->getDateReply()."\n");
print($REP1->getBody()."\n");

$REP1->setIdReply(2);
$REP1->setDateReply(new DateTime('14-01-2023'));
$REP1->setBody('corpo2');

print($REP1->getIdReply()."\n");
print($REP1->getDateReply()."\n");
print($REP1->getBody()."\n");

print_r($REP1->jsonSerialize());*/