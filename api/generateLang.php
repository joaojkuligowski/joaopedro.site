<?php

class Translator
{
  // Get in https://aistudio.google.com/app/apikey
  private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent';
  private $apiKey = 'YOUR_API_KEY';
  private $langDir = 'lang/pt-br';
  private $prompt = [];

  public function translate($text, $targetLanguage)
  {
    $translations = $this->loadTranslations($targetLanguage);

    if (isset($translations[$text])) {
      return $translations[$text];
    }

    return $this->callApi($text);
  }

  private function loadTranslations($language)
  {
    $filePath = $this->langDir . "/lang/{$language}.json"; // Supondo que as traduções estão em formato JSON
    if (file_exists($filePath)) {
      $json = file_get_contents($filePath);
      return json_decode($json, true);
    }
    return [];
  }

  public function setPrompt($text)
  {
    $this->prompt['contents']['parts'][]['text'] = $text;

    return $this;
  }

  public function callApi()
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->apiUrl . '?key=' . $this->apiKey);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->prompt));
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'x-goog-api-key: ' . $this->apiKey
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
  }

  public function generateFile($originLang, $targetLang)
  {
    $originLangFile = 'lang/' . $originLang . '.json';
    $targetLangFile = 'lang/' . $targetLang . '.json';
    if (file_exists($targetLangFile)) {
      return true;
    }

    $fileContent = file_get_contents($originLangFile);

    $this->setPrompt('Analise o arquivo abaixo e traduza-o.');
    $this->setPrompt('Lingua de Origem: pt-BR');
    $this->setPrompt('Lingua de Destino: ' . $targetLang);
    $this->setPrompt('conteudo do arquivo de origem: ' . $fileContent);
    $this->setPrompt('retorne o arquivo no mesmo formato em json, mas com o conteudo traduzido.');

    $translatedText = $this->callApi();

    $jsonReturned = $translatedText['candidates'][0]['content']['parts'][0]['text'];
    $jsonReturned = preg_replace('/\s+/', ' ', $jsonReturned);

    $start = strpos($jsonReturned, '{');
    $end = strrpos($jsonReturned, '}');
    $jsonReturned = substr($jsonReturned, $start, $end - $start + 1);

    $arrayData = json_decode($jsonReturned, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return false;
    }

    file_put_contents($targetLangFile, json_encode($arrayData, JSON_PRETTY_PRINT));

    return true;
  }
}

$translator = new Translator();

$locales = [
  'pt-br',
  'en',
  'es',
  'fr',
  'de',
  'it',
  'ru',
  'ja',
  'ko',
  'zh-cn',
  'zh-tw'
];

foreach ($locales as $locale) {
  echo "Traduzindo para $locale..." . PHP_EOL;
  $translator->generateFile('pt-br', $locale);
}
