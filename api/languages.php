<?php
header('Content-Type: application/json; charset=utf-8');

$lang = $_GET['lang'] ?? 'default'; // Define um padrão se o lang não for fornecido
$lang = preg_replace('/[^a-zA-Z]/', '', $lang); // Remove caracteres indesejados para segurança

$filePath = __DIR__ . '/public/lang/' . $lang . '.json';

if (file_exists($filePath)) {
  echo file_get_contents($filePath);
} else {
  echo json_encode(['error' => 'Language file not found']);
}
