<?php

namespace OpenSynergic\ThemesManager;

use OpenSynergic\ThemesManager\Commands;
use Spatie\LaravelPackageTools\Package;
use OpenSynergic\ThemesManager\Facades\Themes;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ThemesManagerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'themes-manager';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasRoute('web')
            ->hasMigration('create_themes_settings')
            ->hasCommands($this->getCommands())
            ->hasConfigFile();
    }

    protected function getCommands(): array
    {
        return [
            Commands\CacheTheme::class,
            Commands\ClearCacheTheme::class,
            Commands\EnableTheme::class,
            Commands\MakeTheme::class,
            Commands\ListTheme::class,
        ];
    }

    public function packageBooted()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->package->basePath('/../stubs') => base_path("stubs/{$this->package->shortName()}"),
            ], "{$this->package->shortName()}-stubs");
        }
    }

    public function packageRegistered(): void
    {
        $this->app->beforeResolving('view.finder', function (): void {
            Themes::init();
        });

        $this->app->bind(ThemesManager::class, function (): ThemesManager {
            return new ThemesManager;
        });
    }
}
