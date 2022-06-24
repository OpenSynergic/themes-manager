<?php

namespace OpenSynergic\ThemesManager\Concerns\Theme;

use OpenSynergic\Hooks\Facades\Hook;

trait CanInitialize
{
  public function init(): void
  {
  }

  final public function initialize(): void
  {
    Hook::call('theme.beforeInit', $this);

    $this->init();

    Hook::call('theme.afterInit', $this);
  }
}
