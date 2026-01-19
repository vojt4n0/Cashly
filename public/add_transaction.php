<?php

session_start();
header("Content-Type: application/json");
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Nejste přihlášen."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'];
$type = $data['type'] ?? 'vydaj';
$amount = floatval($data['amount'] ?? 0);
$category = !empty($data['category']) ? intval($data['category']) : null;
$date = $data['date'] ?? date('Y-m-d');
$description = trim($data['description'] ?? '');

if ($amount <= 0) {
    echo json_encode(["success" => false, "message" => "Částka musí být větší než 0."]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO transakce (uzivatel_id, typ, castka, kategorie_id, datum, popis) VALUES (?, ?, ?, ?, ?, ?)");

if ($stmt->execute([$user_id, $type, $amount, $category, $date, $description])) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Chyba při ukládání do databáze."]);
}
exit;
