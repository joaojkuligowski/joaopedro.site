<?php
const PUBLIC_URL = 'https://joaopedro-site.vercel.app';
const API_URL = 'https://joaopedro-site.vercel.app';
?>
<!DOCTYPE html>
<html id="html-lang" lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>joaopedro.site</title>
  <link rel="stylesheet" href="<?= API_URL ?? PUBLIC_URL ?>/assets/css/styles-min-css">
  <!-- TESTE -->
  <meta name="google-adsense-account" content="ca-pub-7373197968073643">
  <script src="<?= API_URL ?? PUBLIC_URL ?>/assets/js/scripts-min-js"></script>
  <script src="<?= API_URL ?? PUBLIC_URL ?>/assets/js/site-min-js"></script>
</head>

<body class="bg-gray-100 font-sans">
  <div id="app" class="container mx-auto px-4 py-8"></div>
</body>

</html>
