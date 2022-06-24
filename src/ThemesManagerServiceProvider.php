<?php

namespace OpenSynergic\ThemesManager;

use Exception;
use Filament\PluginServiceProvider;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use OpenSynergic\Hooks\Facades\Hook;
use Spatie\LaravelPackageTools\Package;
use OpenSynergic\ThemesManager\Commands\ThemesManagerCommand;
use OpenSynergic\ThemesManager\Facades\Themes;
use OpenSynergic\ThemesManager\Filament\Pages\Themes as PagesThemes;

class ThemesManagerServiceProvider extends PluginServiceProvider
{
    public static string $name = 'themes-manager';

    protected array $styles = [
        'themes-manager' => __DIR__ . '/../dist/app.css',
    ];

    protected array $pages =  [
        PagesThemes::class,
    ];


    public function packageConfigured(Package $package): void
    {
        $package
            ->hasRoute('web')
            ->hasMigration('create_themes_settings')
            ->hasCommand(ThemesManagerCommand::class);
    }

    protected function getPages(): array
    {
        if (config("themes-manager.register_pages")) {
            return $this->pages;
        }


        return [];
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->beforeResolving('filament', function () {
            Livewire::component(PagesThemes::getName(), PagesThemes::class);
        });

        $this->app->beforeResolving('view.finder', function (): void {
            // This is a hack to make sure the view finder is aware of the themes
            Themes::init();
        });

        $this->app->singleton(ThemesManager::class, function (): ThemesManager {
            return new ThemesManager;
        });

        Hook::register('theme.beforeInit', fn ($arguments) => $this->beforeInitTheme($arguments[0]));
    }

    protected function beforeInitTheme(Theme $theme): void
    {
        if ($theme->hasParent()) {
            try {
                $theme->getParent()->initialize();
            } catch (\Throwable $th) {
                throw new Exception(__('themes-manager::exception.parent_theme_not_found', [
                    'parent' => $theme->parent,
                ]));
            }
        }
        // Register the theme locale
        $lang = app(Translator::class);
        $lang->addNamespace($theme->getLangNamespace(), $theme->getLangPath());

        // Register the theme views
        Config::set('view.paths', array_merge([$theme->getResourceViewPath()], Config::get('view.paths')));
    }
}
