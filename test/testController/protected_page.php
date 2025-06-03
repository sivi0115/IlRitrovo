<?php

namespace Controller;
namespace Utility;

use Controller\CUser;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../src/Controller/CUser.php';
require_once __DIR__ . '/../../src/Utility/USessions.php';

session_start();


USessions::setSessionElement('idUser', 1);

$controller = new CUser;
$controller->isLogged();  // Se non loggato, redirect

// Se arrivo qui, l’utente è loggato
?>

<!DOCTYPE html>
<html lang="it">
<head><title>Area protetta</title></head>
<body>
  <h1>Benvenuto nell'area riservata!</h1>
  <p>Accesso autorizzato: l'utente è loggato.</p>
</body>
</html>