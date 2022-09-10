<?php

namespace OpenSynergic\ThemesManager\Commands;

use Illuminate\Console\Command;
use OpenSynergic\ThemesManager\Theme;
use OpenSynergic\ThemesManager\ThemesManager;

class ListTheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'themes-manager:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List Theme';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ThemesManager $themesManager)
    {
        $data = collect($themesManager->get())->map(function (Theme $theme) {
            return [
                'name' => $theme->getName(),
                'version' => $theme->getVersion(),
                'description' => $theme->getDescription() ?: 'null',
                'parent' => $theme->get('parent') ?: 'null',
                'active' => $theme->isActive() ? 'true' : 'false',
                'author_name' => $theme->get('author.name') ?: 'null',
                'author_email' => $theme->get('author.email') ?: 'null',
            ];
        })->toArray();

        $this->table(
            ['Name', 'Version', 'Description', 'Parent', 'Active', 'Author Name', 'Author Email'],
            $data
        );

        return 0;
    }
}
