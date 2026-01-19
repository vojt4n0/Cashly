<?php
session_start();
header("Content-Type: application/json");
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Nepřihlášen"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user_id'];

$old_pass = trim($data['old_password'] ?? '');
$new_pass = trim($data['new_password'] ?? '');
$confirm_pass = trim($data['confirm_password'] ?? '');

if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
    echo json_encode(["success" => false, "message" => "Vyplňte všechna pole."]);
    exit;
}

if ($new_pass !== $confirm_pass) {
    echo json_encode(["success" => false, "message" => "Nová hesla se neshodují."]);
    exit;
}

if (strlen($new_pass) < 6 || !preg_match('/[A-Z]/', $new_pass)) {
    echo json_encode(["success" => false, "message" => "Nové heslo musí mít 6+ znaků a velké písmeno."]);
    exit;
}

$stmt = $conn->prepare("SELECT heslo FROM uzivatele WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($old_pass, $user['heslo'])) {
    echo json_encode(["success" => false, "message" => "Současné heslo není správné."]);
    exit;
}

$new_hashed = password_hash($new_pass, PASSWORD_DEFAULT);

$updateStmt = $conn->prepare("UPDATE uzivatele SET heslo = ? WHERE id = ?");

if ($updateStmt->execute([$new_hashed, $user_id])) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Chyba databáze."]);
}
exit;
