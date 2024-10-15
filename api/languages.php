<?php
if ($_GET['lang']) {
  header('Content-Type: application/json');
  echo file_get_contents('lang/' . $_GET['lang'] . '.json');
}
