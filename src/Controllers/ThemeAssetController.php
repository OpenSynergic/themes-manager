<?php

namespace OpenSynergic\ThemesManager\Controllers;

use Illuminate\Support\Str;
use OpenSynergic\ThemesManager\Theme;
use OpenSynergic\ThemesManager\Facades\Themes;

class ThemeAssetController
{
  public function __invoke(string $themeName, string $fileName, $contentType = null)
  {
    $theme = Themes::get($themeName);
    $file = $theme->getPublicPath($fileName);
    if (!file_exists($file) && $theme->hasParent()) {
      $file = $this->getParentThemeAsset($theme, $fileName);
    }

    switch (true) {
      case Str::endsWith($file, '.js'):
        $contentType = 'application/javascript; charset=utf-8';
        break;
      case Str::endsWith($file, '.css'):
        $contentType = 'text/css; charset=utf-8';
        break;
    }

    return $this->pretendResponseIsFile($file, $contentType);
  }

  public function getParentThemeAsset(Theme $theme, string $fileName): ?string
  {
    $parent = $theme->getParent();
    $file = $parent->getPublicPath($fileName);
    if (!file_exists($file) && $parent->hasParent()) {
      $file = $this->getParentThemeAsset($theme, $file);
    }

    return $file;
  }

  protected function getHttpDate(int $timestamp)
  {
    return sprintf('%s GMT', gmdate('D, d M Y H:i:s', $timestamp));
  }

  protected function pretendResponseIsFile(string $path, string $contentType = null)
  {
    abort_unless(
      file_exists($path) || file_exists($path = base_path($path)),
      404,
    );

    if (!$contentType) {
      $contentType = mime_content_type($path);
    }

    $cacheControl = 'public, max-age=31536000';
    $expires = strtotime('+1 year');
    $lastModified = filemtime($path);

    if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '') === $lastModified) {
      return response()->noContent(304, [
        'Expires' => $this->getHttpDate($expires),
        'Cache-Control' => $cacheControl,
      ]);
    }

    return response()->file($path, [
      'Content-Type' => $contentType,
      'Expires' => $this->getHttpDate($expires),
      'Cache-Control' => $cacheControl,
      'Last-Modified' => $this->getHttpDate($lastModified),
    ]);
  }
}
