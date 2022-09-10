<?php

namespace OpenSynergic\ThemesManager\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use OpenSynergic\ThemesManager\ThemesManager;
use OpenSynergic\ThemesManager\Concerns\Commands\CanValidateInput;
use OpenSynergic\ThemesManager\Concerns\Commands\CanManipulateFiles;

class MakeTheme extends Command
{
    use CanValidateInput, CanManipulateFiles;

    protected $signature = 'themes-manager:make';

    protected $description = 'Create a theme';

    protected Filesystem $files;

    protected ThemesManager $themesManager;

    protected array $theme;

    /**
     * Theme folder path.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $filesystem, ThemesManager $themesManager)
    {
        $this->files = $filesystem;
        $this->themesManager = $themesManager;
        $this->basePath = $themesManager->getThemePath();

        $this->theme = [
            'name' => $this->askRequired('Theme Name', 'name'),
            'description' => $this->ask('Theme Description'),
            'version' => $this->ask('Theme Version', "1.0.0"),
            'parent' => $this->askParent(),
            'author' => [
                'name' => $this->ask('Author Name'),
                'email' => $this->ask('Author Email')
            ],
            'license' => $this->ask('License', 'MIT'),
        ];

        try {
            $this->info('Generating necessary files');

            $this->create();

            $this->themesManager->clearCache();

            $this->info("Success create new theme : {$this->theme['name']}");
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }



        return 0;
    }

    protected function askParent()
    {
        if (!$parentName = $this->ask('Parent Theme')) {
            return $parentName;
        };

        $parentTheme = $this->themesManager->get($parentName);
        if (!$parentTheme) {
            $this->error("Parent Theme $parentName not found");

            return $this->askParent();
        }

        return $parentName;
    }

    protected function create()
    {
        $themeDir = $this->basePath . DIRECTORY_SEPARATOR . Str::camel($this->theme['name']);

        if ($this->files->isDirectory($themeDir)) {
            throw new \Exception("Theme {$this->theme['name']} already exists");
        }

        $this->files->makeDirectory($themeDir, 0755, true);

        if (!$this->files->exists($source = base_path("stubs/themes-manager/theme"))) {
            $source = __DIR__ . '/../../stubs/theme';
        }

        $this->files->copyDirectory($source, $themeDir, null);

        $this->copyStubToApp('theme', $themeDir . '/theme.json', ['json' => json_encode($this->theme, JSON_PRETTY_PRINT)]);
    }
}
