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

$trans_id = intval($data['id']);

try {
    //Mazání transakce
    $stmt = $conn->prepare("DELETE FROM transakce WHERE id = ? AND uzivatel_id = ?");

    if ($stmt->execute([$trans_id, $user_id])) {
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Transakce nenalezena nebo nemáte oprávnění."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Chyba databáze."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Chyba: " . $e->getMessage()]);
}
