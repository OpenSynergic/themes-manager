<?php

namespace OpenSynergic\ThemesManager\Concerns\Theme;

trait CanInitialize
{
  public function init(): void
  {
  }

  final public function initialize(): void
  {
    $this->beforeInit();

    $this->init();

    $this->afterInit();
  }

  protected function beforeInit(): void
  {
  }

  protected function afterInit(): void
  {
  }
}
