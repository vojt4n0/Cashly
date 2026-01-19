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

if (empty($data['id'])) {
    echo json_encode(["success" => false, "message" => "Chybí ID transakce."]);
    exit;
}

// Příprava a vstupních dat
$trans_id = intval($data['id']);
$type = $data['type'] ?? 'vydaj';
$amount = floatval($data['amount'] ?? 0);
$category = !empty($data['category']) ? intval($data['category']) : null;
$date = $data['date'] ?? date('Y-m-d');
$description = trim($data['description'] ?? '');

if ($amount <= 0) {
    echo json_encode(["success" => false, "message" => "Částka musí být větší než 0."]);
    exit;
}
if (empty($date)) {
    echo json_encode(["success" => false, "message" => "Zadejte datum."]);
    exit;
}

try {
    //aktualizace záznamu.
    $sql = "UPDATE transakce 
            SET typ = ?, castka = ?, kategorie_id = ?, datum = ?, popis = ? 
            WHERE id = ? AND uzivatel_id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$type, $amount, $category, $date, $description, $trans_id, $user_id])) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Chyba databáze."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Chyba: " . $e->getMessage()]);
}
