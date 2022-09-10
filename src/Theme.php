<?php

namespace OpenSynergic\ThemesManager;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use OpenSynergic\ThemesManager\Facades\Themes;
use Illuminate\Contracts\Translation\Translator;
use OpenSynergic\ThemesManager\Concerns\Theme\{
  CanInitialize,
  HasCover,
  HasJson,
  HasOptions,
  HasParent,
  HasPath
};

class Theme
{
  use HasJson,
    HasParent,
    HasPath,
    HasOptions,
    HasCover,
    CanInitialize;

  public function getThemeName()
  {
    $name = app(Filesystem::class)->name($this->getPath());
    return $name;
  }

  protected function beforeInit(): void
  {
    if ($this->hasParent()) {
      try {
        $this->getParent()->initialize();
      } catch (\Throwable $th) {
        throw new \Exception(__('themes-manager::exception.parent_theme_not_found', [
          'parent' => $this->parent,
        ]));
      }
    }
    // Register the theme locale
    $lang = app(Translator::class);
    $lang->addNamespace($this->getLangNamespace(), $this->getLangPath());

    // Register the theme views
    Config::set('view.paths', array_merge([$this->getResourceViewPath()], Config::get('view.paths')));
  }

  public function getLangNamespace()
  {
    return $this->getThemeName();
  }

  function isActive()
  {
    return Themes::getActiveThemeName() === $this->getThemeName();
  }
}
