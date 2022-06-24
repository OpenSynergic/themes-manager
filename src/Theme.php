<?php

namespace OpenSynergic\ThemesManager;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use OpenSynergic\ThemesManager\Facades\Themes;
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
    return Str::snake(Str::lower($name));
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
