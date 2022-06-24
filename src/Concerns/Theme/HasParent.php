<?php

namespace OpenSynergic\ThemesManager\Concerns\Theme;

use OpenSynergic\ThemesManager\Facades\Themes;
use OpenSynergic\ThemesManager\Theme;

trait HasParent
{
  /**
   * Check if has parent Theme.
   */
  public function hasParent(): bool
  {
    return !is_null($this->parent);
  }

  /**
   * Get parent Theme.
   *
   * @return null|Theme
   */
  public function getParent(): null|Theme
  {
    return Themes::get($this->parent);
  }
}
