<?php
require_once 'EReply.php';
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

print_r($REP1->jsonSerialize());