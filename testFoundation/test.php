<?php
require_once 'C:\Users\none\Desktop\EventHubMio\EventHub\vendor\autoload.php';

use Foundation\FAdmin;
use Foundation\FDatabase;


$db = FDatabase::getInstance();
$idAdmin = 17;
$newPhone = '0987654321';

// Query SQL
$sql = "UPDATE admin SET phone = :phone WHERE idAdmin = :idAdmin";

// Prepara la query
$stmt = $db->prepare($sql);

// Bind dei parametri
$stmt->bindValue(':phone', $newPhone);
$stmt->bindValue(':idAdmin', $idAdmin, PDO::PARAM_INT);

// Esecuzione della query
if ($stmt->execute()) {
    echo "Aggiornamento riuscito\n";
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Errore SQL: " . $errorInfo[2] . "\n";
}

// Verifica il risultato
$stmt = $db->prepare("SELECT phone FROM admin WHERE idAdmin = :idAdmin");
$stmt->bindValue(':idAdmin', $idAdmin, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Phone after update: " . $result['phone'] . "\n";
