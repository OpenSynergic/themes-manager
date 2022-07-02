<?php

namespace OpenSynergic\ThemesManager\Filament\Pages;

use ZipArchive;
use ZanySoft\Zip\Zip;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Filament\Pages\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use OpenSynergic\ThemesManager\Theme;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;
use Illuminate\View\ComponentAttributeBag;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use OpenSynergic\ThemesManager\Facades\Themes as FacadesThemes;

class Themes extends Page implements HasForms
{
  use InteractsWithForms;

  public $data;
  public $option = null;

  protected static ?string $navigationIcon = 'heroicon-o-view-grid-add';

  protected static string $view = 'themes-manager::filament.pages.themes';

  function mount()
  {
  }

  protected function getHeading(): string
  {
    return $this->option ? FacadesThemes::get($this->option)->getName() : parent::getHeading();
  }

  protected function getViewData(): array
  {
    return [
      'themes' => FacadesThemes::get(),
    ];
  }

  public function options($themeName): void
  {
    $this->option = $themeName;

    $this->form->fill(FacadesThemes::get($this->option)->getOptionsFillForm());
  }

  protected function getActions(): array
  {
    return [
      Action::make('back')
        ->hidden(fn () => $this->option === null)
        ->button()
        ->color('secondary')
        ->icon('heroicon-o-arrow-left')
        ->label(__('themes-manager::pages.actions.back.label'))
        ->action(fn () => $this->option = null),
      Action::make('install')
        ->button()
        ->hidden(fn () => $this->option !== null)
        ->icon('heroicon-o-upload')
        ->label(__('themes-manager::pages.actions.install.label'))
        ->action(function (array $data) {
          $fileName = $data['attachment'];
          $filePath = Storage::disk('local')->path($fileName);
          try {
            $zip = Zip::open($filePath);
            if (!$zip->has('theme.json', ZipArchive::FL_NODIR)) {
              throw new \Exception(__('themes-manager::pages.notifications.zip_is_not_valid_theme'));
            }

            $zip->extract(config('themes-manager.path'));
            $zip->close();

            Storage::disk('local')->delete($fileName);
          } catch (\Exception $th) {
            Log::error($th);

            $this->notify('danger', $th->getMessage());
            return;
          }

          $this->notify('success', __('themes-manager::pages.notifications.installed'));
          // reload page
          return redirect(request()->header('Referer'));
        })
        ->outlined()
        ->form([
          FileUpload::make('attachment')
            ->disk('local')
            ->disableLabel()
            ->directory('theme-tmp')
            ->acceptedFileTypes(['application/zip'])
        ]),
      Action::make('delete')
        ->label(__('themes-manager::pages.actions.option.delete.label'))
        ->hidden(fn () => $this->option === null)
        ->button()
        ->color('danger')
        ->requiresConfirmation()
        ->icon('heroicon-o-trash')
        ->action(function () {
          try {
            if ($this->option === null) {
              throw new \Exception(__('themes-manager::exception.no_theme_selected'));
            }

            $theme = FacadesThemes::get($this->option);
            $fileSystem = app(Filesystem::class);
            $fileSystem->deleteDirectory($theme->getPath());
          } catch (\Throwable $th) {
            $this->notify('danger', $th->getMessage());
            return;
          }

          $this->notify('success', __('themes-manager::pages.notifications.uninstalled'));
          return redirect(request()->header('Referer'));
        })

    ];
  }

  public function enable($themeName)
  {
    FacadesThemes::enable($themeName);

    $this->notify('success', __('themes-manager::pages.notifications.enabled'));

    return redirect(request()->header('Referer'));
  }

  public function optionsButtonAttributeBag(Theme $theme): ComponentAttributeBag
  {
    $themeName = $theme->getThemeName();

    return $this->getAttributeBag([
      'wire:click' => "options('$themeName')",
    ]);
  }

  public function enableButtonAttributeBag(Theme $theme): ComponentAttributeBag
  {
    $themeName = $theme->getThemeName();

    return $this->getAttributeBag([
      'wire:click' => "enable('$themeName')",
    ]);
  }

  public function getAttributeBag(array $attributes): ComponentAttributeBag
  {
    return new ComponentAttributeBag($attributes);
  }

  protected function getFormStatePath(): string
  {
    return 'data';
  }

  protected static function getNavigationBadge(): ?string
  {
    return FacadesThemes::count();
  }

  protected function getFormSchema(): array
  {
    return $this->option ? FacadesThemes::get($this->option)->getOptionsFormSchema() : [];
  }

  public function submitOptions()
  {
    $data = $this->form->getState();

    try {
      FacadesThemes::get($this->option)->submitOptionsForm($data);
      $this->option = null;
      $this->notify('success', __('themes-manager::pages.notifications.options_saved'));
      return redirect(request()->header('Referer'));
    } catch (\Throwable $th) {
      Log::error($th);
      $this->notify('danger', __('themes-manager::pages.notifications.options_not_saved'));
    }
  }
}
