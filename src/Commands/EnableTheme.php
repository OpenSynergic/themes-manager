<?php

namespace OpenSynergic\ThemesManager\Commands;

use Illuminate\Console\Command;
use OpenSynergic\ThemesManager\ThemesManager;

class EnableTheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'themes-manager:enable {theme}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable Theme';

    /**
     * Execute the console command.
     * @param OpenSynergic\ThemesManager\ThemesManager
     * @return int
     */
    public function handle(ThemesManager $themesManager)
    {
        $themeName = $this->argument('theme');
        if (!$themesManager->get($themeName)) {
            $this->error("Can't enable theme, Theme $themeName not found.");
            return 1;
        }

        $themesManager->enable($themeName);

        $this->info("Enable theme $themeName success.");
        return 0;
    }
}
