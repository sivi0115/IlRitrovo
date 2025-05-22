<?php
require_once 'EReview.php';
use Entity\EReview;

$REV1= new EReview(1,
                1,
                1,
                'Questo locale fa schifo',
                 new DateTime('12-01-2020'),
                 null );

print($REV1->getIdUser()."\n");
print($REV1->getIdReview()."\n");
print($REV1->getIdReply()."\n");
print($REV1->getStars()."\n");
print($REV1->getBody()."\n");
print($REV1->getCreationTime()."\n");

$REV1->setIdUser(2);
$REV1->setIdReview(2);
$REV1->setIdReply(2);
$REV1->setStars(5);
$REV1->setBody('Wow che bel locale');
$REV1->setCreationTime(new DateTime('12-02-2025'));

print($REV1->getIdUser()."\n");
print($REV1->getIdReview()."\n");
print($REV1->getIdReply()."\n");
print($REV1->getStars()."\n");
print($REV1->getBody()."\n");
print($REV1->getCreationTime()."\n");

print_r($REV1->jsonSerialize());