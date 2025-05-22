<?php
require_once 'EReservation.php';
require_once 'EExtra.php';

use Entity\EReservation;
use Entity\TimeFrame;
use Entity\EExtra;

$E1= new EReservation(1,
                        1,
                        null,
                        1,
                        new DateTime('14-01-2026'),
                        new DateTime('15-01-2026'),
                        TimeFrame::CENA,
                        'pending',
                        49.99,
                        4,
                        "Non so chi ha avuto l'idea di prenotare in questo posto di merda");

$extra1 = new EExtra(1,
                    'fiori',
                    49.99);

$extra2 = new EExtra(2,
                    'palloncini',
                    10);

$E1->setExtras([$extra1, $extra2]);

print($E1->getIdReservation()."\n");
print($E1->getIdUser()."\n");
print($E1->getIdTable()."\n");
print($E1->getIdRoom()."\n");
print($E1->getCreationTime()."\n");
print($E1->getReservationDate()."\n");
print($E1->getReservationTimeFrame()."\n");
print($E1->getState()."\n");
print($E1->getTotPrice()."\n");
print($E1->getPeople()."\n");
print($E1->getComment()."\n");
$extras = $E1->getExtras();
foreach ($extras as $e) {
    print("Extra price: " . $e->getPriceExtra() . "\n");
}

// Calcolo del totale iniziale
$initialTotal = $E1->calculateTotPriceFromExtras();
echo "Totale iniziale: $initialTotal\n";

$E1->setIdReservation(2);
$E1->setIdUser(2);
$E1->setIdTable(1);
$E1->setIdRoom(null);
$E1->setCreationTime(new DateTime('11-02-2025'));
$E1->setReservationDate(new DateTime('12-02-2025'));
$E1->setReservationTimeFrame(TimeFrame::PRANZO);
$E1->setState('approved');
$E1->setTotPrice(2);
$E1->setPeople(5);
$E1->setComment('richiesto machete per ospiti insopportabili');
// Aggiornamento del prezzo del primo extra
$extras = $E1->getExtras();
$extras[0]->setPriceExtra(7.5);
$E1->setExtras($extras);

print($E1->getIdReservation()."\n");
print($E1->getIdUser()."\n");
print($E1->getIdTable()."\n");
print($E1->getIdRoom()."\n");
print($E1->getCreationTime()."\n");
print($E1->getReservationDate()."\n");
print($E1->getReservationTimeFrame()."\n");
print($E1->getState()."\n");
print($E1->getTotPrice()."\n");
print($E1->getPeople()."\n");
print($E1->getComment()."\n");

// Calcolo del nuovo totale
$updatedTotal = $E1->calculateTotPriceFromExtras();
echo "Totale aggiornato: $updatedTotal\n";

print_r($E1->jsonSerialize());