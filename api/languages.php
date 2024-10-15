<?php
header('Content-Type: application/json, charset=utf-8');
if ($_GET['lang']) {
  echo require __DIR__ . '/public/lang/' . basename($_GET['lang'] . '.json');
}
