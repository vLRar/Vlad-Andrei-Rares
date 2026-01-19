<?php
require 'db.php';
echo "Conexiune reușită la baza de date: " . htmlspecialchars($db);

$stmt = $pdo->prepare("SELECT nume_produs FROM Drone WHERE pret = ?");
$Drone = $stmt->fetchAll();

if (!$Drone) {
    echo "⚠️ No Italian books found.";
    exit;
}

foreach ($Drone as $drona) {
    echo htmlspecialchars($drona['nume_produs']) . "<br>";
}

?>