<?php

namespace OpenSynergic\ThemesManager\Settings;

use Spatie\Valuestore\Valuestore;

class ThemesManagerSettings extends Valuestore
{
  /** @var string */
  protected $fileName = __DIR__ . '/../../setting.json';

  public function __construct()
  {
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
