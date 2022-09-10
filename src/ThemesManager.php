<?php

namespace OpenSynergic\ThemesManager;

use Countable;
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
    collect($this->getThemesDir())
      ->filter(fn ($path) => $filesystem->exists($path . DIRECTORY_SEPARATOR . 'theme.json'))
      ->mapWithKeys(function ($path) use ($filesystem) {
        $themeFile = $path . DIRECTORY_SEPARATOR . 'theme.php';

        $theme     = $filesystem->exists($themeFile) ? $filesystem->requireOnce($path . DIRECTORY_SEPARATOR . 'theme.php') : new Theme;
        return [$path =>  $theme];
      })
      ->filter(fn ($class) => $class instanceof Theme)
      ->each(function (Theme $theme, string $path) {
        $theme->path($path);
        $this->register($theme);
      });
  }

  protected function getThemesDir(): array
  {
    return Cache::remember('themes-dir', 3600, function () {
      $filesystem = app(Filesystem::class);
      $filesystem->ensureDirectoryExists($this->getThemePath());
      return $filesystem->directories($this->getThemePath());
    });
  }

  public function getThemePath(): string
  {
    return base_path(config('themes-manager.dir'));
  }

  public function count(): int
  {
    return count($this->themes);
  }

  /**
   * Get all themes or a specific one.
   * @param string|null $name
   * @return Theme|array|null
   */
  public function get($name = null): Theme|array|null
  {
    if (is_null($name)) {
      return $this->themes;
    }

    return $this->themes[$name] ?? null;
  }


  /**
   * Get current active theme.
   */
  public function getActiveTheme(): ?Theme
  {
    return $this->get($this->getActiveThemeName());
  }

  public function getActiveThemeName(): ?string
  {
    return $this->setting->active_theme;
  }

  /**
   * Register a theme.
   * @param Theme $theme
   */
  public function register(Theme $theme): void
  {
    $this->themes[$theme->getThemeName()] = $theme;
  }

  /**
   * Initialize active theme.
   * @param string $name
   * @return void
   */
  public function init(): void
  {
    if (!$this->getActiveThemeName()) return;
    $this->getActiveTheme()?->initialize();
  }

  public function enable($themeName): void
  {
    $this->setting->active_theme = $themeName;
  }

  public function disable(): void
  {
    $this->setting->active_theme = null;
  }

  public function url($file): ?string
  {
    $theme = $this->getActiveTheme();
    return $theme?->asset($file);
  }

  public function clearCache(): void
  {
    Cache::forget('themes-dir');
  }
}
