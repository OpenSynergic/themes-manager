<?php

namespace OpenSynergic\ThemesManager\Settings;

use Spatie\Valuestore\Valuestore;

class ThemesManagerSettings extends Valuestore
{
  public function __construct()
  {
    $this->fileName = base_path('theme-setting.json');
  }

  public function __get($name)
  {
    return $this->get($name);
  }

  public function __set($name, $value = null)
  {
    $this->put($name, $value);
  }
}
