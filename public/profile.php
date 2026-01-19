<?php
session_start();
header("Content-Type: application/json");
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Nepřihlášen"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT email, vytvoreno FROM uzivatele WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$countStmt = $conn->prepare("SELECT COUNT(*) as count FROM transakce WHERE uzivatel_id = ?");
$countStmt->execute([$user_id]);
$count = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];


if ($user) {
    $date = date('d. m. Y', strtotime($user['vytvoreno']));

    echo json_encode([
        "success" => true,
        "email" => $user['email'],
        "date" => $date,
        "transaction_count" => $count
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Uživatel nenalezen"]);
}
exit;
