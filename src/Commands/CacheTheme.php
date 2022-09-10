<?php

namespace OpenSynergic\ThemesManager\Commands;

use Illuminate\Console\Command;
use OpenSynergic\ThemesManager\ThemesManager;

class CacheTheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'themes-manager:cache {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ThemesManager $themesManager)
    {
        if ($this->option('clear')) {
            $themesManager->clearCache();
            $this->info('Themes Manager cache cleared');
            return 0;
        }

        $this->info('Themes Manager cached');
        return 0;
    }
}
