<?php

namespace OpenSynergic\ThemesManager;

use Countable;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\Macroable;
use OpenSynergic\ThemesManager\Settings\ThemesManagerSettings;

class ThemesManager implements Countable
{
  use Macroable;

  protected array $themes = [];
  protected ThemesManagerSettings $setting;

  public function __construct()
  {
    $this->discover();

    $this->setting = new ThemesManagerSettings();
  }

  /**
   * @var Filesystem
   */
  public function discover(): void
  {
    $filesystem = app(Filesystem::class);
    $filesystem->ensureDirectoryExists(config('themes-manager.path'));

    collect($filesystem->directories(config('themes-manager.path')))
      ->filter(fn ($path) => $filesystem->exists($path . DIRECTORY_SEPARATOR . 'theme.json'))
      ->mapWithKeys(function ($path) use ($filesystem) {
        $themeFile = $path . DIRECTORY_SEPARATOR . 'theme.php';
        $theme = $filesystem->exists($themeFile) ? $filesystem->requireOnce($path . DIRECTORY_SEPARATOR . 'theme.php') : new Theme;
        return [$path =>  $theme];
      })
      ->filter(fn ($class) => $class instanceof Theme)
      ->each(function (Theme $theme, string $path) {
        $theme->path($path);
        $this->register($theme);
      });
  }

  public function count(): int
  {
    return count($this->themes);
  }

  /**
   * Get all themes or a specific one.
   * @param string|null $name
   * @return null|Theme|array
   */
  public function get($name = null)
  {
    if (is_null($name)) {
      return $this->themes;
    }

    return $this->themes[$name] ?? null;
  }

  /**
   * Get current active theme.
   */
  public function getActive()
  {
    return $this->get($this->getActiveThemeName());
  }

  public function getActiveThemeName()
  {
    return $this->setting->active_theme;
  }

  /**
   * Register a theme.
   * @param Theme $theme
   */
  public function register(Theme $theme)
  {
    $this->themes[$theme->getThemeName()] = $theme;
  }

  /**
   * Initialize active theme.
   * @param string $name
   * @return string
   */
  public function init()
  {
    if (!$this->getActiveThemeName()) return;
    $this->getActive()?->initialize();
  }

  public function enable($themeName)
  {
    $this->setting->active_theme = $themeName;
  }

  public function disable()
  {
    $this->setting->active_theme = null;
  }

  public function url($file, $version = true)
  {
    $theme = $this->getActive();
    return $theme->asset($file, $version);
  }
}
