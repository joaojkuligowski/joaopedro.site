<?php
$isVercel = $_GET['isVercel'] ?? false;
$path =  $isVercel ? $_GET['path'] : $_SERVER['REQUEST_URI'];
$uriFragments = explode('/api/assets', $path)[1];
if (strpos($uriFragments, '/') !== false) {
  $assetType = explode('/', $uriFragments)[1];
  $assetType = str_replace('-', '.', $assetType);

  $assetFile = explode('/', $uriFragments)[2];
  $assetFile = str_replace('-', '.', $assetFile);
  if ($assetType === 'css') {
    header("Content-type: text/css; charset: UTF-8");
    echo require '../public/css/' . $assetFile;
  } else if ($assetType === 'js') {
    header('Content-Type: application/javascript; charset: UTF-8');
    echo require '../public/js/' . $assetFile;
  } else if ($assetType === 'lang') {
    header('Content-Type: application/json; charset: UTF-8');
    echo file_get_contents('../public/lang/' . explode('/', $uriFragments)[2] . '.json');
  }
}
