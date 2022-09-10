<?php

namespace OpenSynergic\ThemesManager\Commands;

use Illuminate\Console\Command;

class ClearCacheTheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'themes-manager:cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache Themes Manager';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('themes-manager:cache', [
            '--clear' => true
        ]);

        return 0;
    }
}
