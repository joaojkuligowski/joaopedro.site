<?php
if ($_GET['lang']) {
  header('Content-Type: application/json');
  echo require __DIR__ . '/public/languages/' . basename($_GET['lang'] . '.json');
}
