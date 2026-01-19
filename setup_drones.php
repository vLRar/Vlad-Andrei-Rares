<?php
require 'db_connect.php';

try {
    // 1. Creare Tabel
    $sql_create = "CREATE TABLE IF NOT EXISTS Drone_Disponibile (
        ID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        Serie VARCHAR(20) NOT NULL,
        Nume VARCHAR(50),
        Marca VARCHAR(50),
        Pret INT
    )";
    $pdo->exec($sql_create);
    echo "Tabelul 'Drone_Disponibile' a fost verificat/creat.<br>";

    // 2. Verificăm dacă e gol ca să nu duplicăm datele
    $stmt = $pdo->query("SELECT COUNT(*) FROM Drone_Disponibile");
    if ($stmt->fetchColumn() == 0) {
        // 3. Inserare Date
        $sql_insert = "INSERT INTO Drone_Disponibile (Serie, Nume, Marca, Pret) VALUES 
            ('MK4P', 'Mavic 4 PRO', 'DJI', 28000),
            ('MK4C', 'Mavic 4 Clasic', 'DJI', 90000),
            ('MK3P', 'Mavic 3 PRO', 'DJI', 285000),
            ('MK3', 'Mavic 3', 'DJI', 45000),
            ('A3', 'Air 3', 'DJI', 5000),
            ('X1', 'X10', 'Skydio', 120000),
            ('ELE', 'EVO LITE ENTERPRISE', 'AUTEL ROBOTICS', 2000)";
        
        $pdo->exec($sql_insert);
        echo "Datele au fost inserate cu succes!";
    } else {
        echo "Tabelul are deja date.";
    }

} catch (PDOException $e) {
    echo "Eroare SQL: " . $e->getMessage();
}
?>  