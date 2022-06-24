<?php

namespace OpenSynergic\ThemesManager\Concerns\Theme;

use OpenSynergic\ThemesManager\Theme;

trait HasPath
{
  protected string $path;

  public function getPath(string $string = null): string
  {
    return $this->path . DIRECTORY_SEPARATOR . $string;
  }

  public function getPublicPath(string $string = null): string
  {
    return $this->getPath('public' . DIRECTORY_SEPARATOR . $string);
  }

  public function path(string $path): Theme
  {
    $this->path = $path;

    return $this;
  }

  public function getResourceViewPath(): string
  {
    return $this->getPath('resources' . DIRECTORY_SEPARATOR . 'views');
  }

  public function getLangPath()
  {
    return $this->getPath('lang');
  }

  public function asset(string $file = null, $version = true): string
  {
    $file = $version ? $file . '?v=' . md5($this->version) : $file;

    return route('themes-manager.asset', [
      'theme' => $this->getThemeName(),
      'file' => $file,
    ]);
  }
}
