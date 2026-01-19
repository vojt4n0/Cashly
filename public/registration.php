<?php
session_start();
header("Content-Type: application/json");
require_once "../includes/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data['username'] ?? '');
$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($email) || empty($password) || empty($username)) {
  echo json_encode(["success" => false, "field" => "password", "message" => "Vyplňte všechna pole."], JSON_UNESCAPED_UNICODE);
  exit;
}
if (strlen($username) < 4) {
  echo json_encode(["success" => false, "field" => "username", "message" => "Jméno musí mít alespoň 4 znaky."], JSON_UNESCAPED_UNICODE);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["success" => false, "field" => "email", "message" => "Neplatný email."], JSON_UNESCAPED_UNICODE);
  exit;
}

if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password)) {
  echo json_encode(["success" => false, "field" => "password", "message" => "Heslo musí mít 6+ znaků a velké písmeno."], JSON_UNESCAPED_UNICODE);
  exit;
}

$stmt = $conn->prepare("SELECT id FROM uzivatele WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
  echo json_encode(["success" => false, "field" => "email", "message" => "Email už je použit."], JSON_UNESCAPED_UNICODE);
  exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO uzivatele (jmeno, email, heslo) VALUES (?, ?, ?)");

if ($stmt->execute([$username, $email, $hashed])) {
  $_SESSION['user_id'] = $conn->lastInsertId();
  $_SESSION['username'] = $username;
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "field" => "email", "message" => "Chyba databáze."], JSON_UNESCAPED_UNICODE);
}
exit;
