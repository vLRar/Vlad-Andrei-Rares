<?php
require 'db.php'; 

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM studenti ORDER BY id DESC");
        echo json_encode($stmt->fetchAll());
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

elseif ($method === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['nume'], $data['anul'], $data['media'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO studenti (nume, anul, media) VALUES (?, ?, ?)");
            $result = $stmt->execute([$data['nume'], $data['anul'], $data['media']]);
            echo json_encode(['success' => $result]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Date incomplete']);
    }
}
?>