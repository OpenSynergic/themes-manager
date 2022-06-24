<x-filament::page class="filament-themes-manager">
    @if (!$option)
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse ($themes as $theme)
                <div
                    class="shadow dark:border-gray-600 dark:bg-gray-800 p-0 bg-gray-500 rounded-lg border border-gray-200 flex flex-col justify-end">
                    <div class="relative overflow-hidden h-full">
                        @if ($theme->isActive())
                            <div class="absolute left-0 top-0 h-16 w-16">
                                <div
                                    class="absolute transform -rotate-45 bg-primary-600 text-center text-white font-semibold py-1 left-[-34px] top-[32px] w-[170px]">
                                    {{ __('themes-manager::pages.active') }}
                                </div>
                            </div>
                        @endif
                        @if ($theme->hasCoverImage())
                            <img class="rounded-t-lg bg-gray-500 h-full w-full" src="{{ $theme->getCoverImageUrl() }}"
                                alt="cover theme" />
                        @else
                            <div class="rounded-t-lg bg-gray-500 h-40 w-full"></div>
                        @endif

                    </div>

                    <div class="p-5 bg-white rounded-b-lg dark:border-gray-600 dark:bg-gray-800">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-300">
                                {{ $theme->name }}
                            </h5>
                            <small class="text-gray-600 dark:text-gray-300">{{ $theme->version }}</small>
                        </div>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $theme->description }}</p>
                        <div class="flex items-center justify-between">
                            <x-filament-support::button :outlined="true" :dark-mode="config('filament.dark_mode')" :attributes="$this->optionsButtonAttributeBag($theme)">
                                {{ __('themes-manager::pages.actions.option.label') }}
                            </x-filament-support::button>
                            @if (!$theme->isActive())
                                <x-filament-support::button :dark-mode="config('filament.dark_mode')" :disabled="$theme->isActive()" :attributes="$this->enableButtonAttributeBag($theme)">
                                    {{ __('themes-manager::pages.actions.enable.label') }}
                                </x-filament-support::button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-56 flex items-center justify-center place-items-center p-4 md:col-span-2 lg:col-span-3">
                    <div class="text-center">
                        <x-heroicon-o-view-grid-add class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 font-medium text-gray-900">
                            {{ __('themes-manager::pages.no_themes_found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('themes-manager::pages.no_themes_found_description') }}
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    @else
        @if (!empty(Themes::get($this->option)->getOptionsFormSchema()))
            <form wire:submit.prevent='submitOptions'>
                {{ $this->form }}
                <div class="flex items-center mt-4 space-x-4">
                    <x-filament-support::button :dark-mode="config('filament.dark_mode')" type="submit" form="submitOptions">
                        {{ __('themes-manager::pages.actions.option.submit.label') }}
                    </x-filament-support::button>
                    <x-filament-support::button :dark-mode="config('filament.dark_mode')" color="secondary" wire:click="$set('option', null)">
                        {{ __('themes-manager::pages.actions.option.cancel.label') }}
                    </x-filament-support::button>
                </div>
            </form>
        @else
            <div class="h-56 flex items-center justify-center place-items-center p-4 md:col-span-2 lg:col-span-3">
                <div class="text-center">
                    <x-heroicon-o-information-circle class="mx-auto h-12 w-12 text-warning-400" />
                    <h3 class="mt-2 font-medium text-gray-900">
                        {{ __('themes-manager::pages.no_options') }}</h3>
                </div>
            </div>
        @endif
    @endif

</x-filament::page>
