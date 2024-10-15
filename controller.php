<?php

class Controller
{
  private $assets = [];

  public function setAsset($type, $url)
  {
    $this->assets[] = ['type' => $type, 'url' => $url];
  }

  public function minify($content)
  {
    // Remover comentários e espaços excessivos, mantendo estrutura básica do CSS/JS
    $content = preg_replace('!/\*.*?\*/!s', '', $content); // Remove comentários
    $content = preg_replace('/\s+/', ' ', $content);        // Reduz espaços
    $content = str_replace(["\n", "\r"], '', $content);     // Remove quebras de linha
    return trim($content);
  }

  public function compile()
  {
    $cssPath = dirname(__FILE__) . '/assets.min.css';
    $jsPath = dirname(__FILE__) . '/assets.min.js';

    $combinedCssContent = '';
    $combinedJsContent = '';

    foreach ($this->assets as $asset) {
      $type = $asset['type'];
      $content = file_get_contents($asset['url']);

      if ($content !== false) {
        if ($type === 'css') {
          $combinedCssContent .= $this->minify($content) . PHP_EOL;
        } elseif ($type === 'js') {
          $combinedJsContent .= $this->minify($content) . PHP_EOL;
        }
      }
    }

    // Gerar arquivo CSS minificado se houver conteúdo
    if ($combinedCssContent !== '') {
      file_put_contents($cssPath, $combinedCssContent);
    }

    // Gerar arquivo JS minificado se houver conteúdo
    if ($combinedJsContent !== '') {
      file_put_contents($jsPath, $combinedJsContent);
    }
  }
}
