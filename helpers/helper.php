<?php

use OpenSynergic\ThemesManager\Theme;

if (!function_exists('theme')) {
  /**
   * Set theme.
   *
   * @param  string  $themeName
   * @return \OpenSynergic\ThemesManager\Theme
   */
  function theme($themeName = null): Theme
  {
    if ($themeName) {
      \Themes::get($themeName);
    }

    return \Themes::getActive();
  }
}

if (!function_exists('theme_asset')) {
  /**
   * Generate an asset url for active theme .
   *
   * @param  string  $asset
   * @param  bool  $version
   * @return string
   */
  function theme_asset(string $asset)
  {
    return \Themes::url($asset);
  }
}
