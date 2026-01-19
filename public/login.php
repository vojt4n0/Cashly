<?php
session_start();

header("Content-Type: application/json");

require_once "../includes/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "field" => "password", "message" => "Vyplňte všechna pole."], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM uzivatele WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["success" => false, "field" => "email", "message" => "Tento email není registrován."], JSON_UNESCAPED_UNICODE);
    exit;
}

if (password_verify($password, $user['heslo'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['jmeno'];
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "field" => "password", "message" => "Nesprávné heslo."], JSON_UNESCAPED_UNICODE);
}
exit;
