<?php

namespace OpenSynergic\ThemesManager\Concerns\Theme;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait HasOptions
{
  protected $table = 'theme_options';

  public function hasOption(string $key): bool
  {
    return $this->getOption($key) !== null;
  }

  public function getAllOptions(): array
  {
    return DB::table($this->table)
      ->select(['key', 'value'])
      ->where('name', $this->getThemeName())
      ->get()
      ->mapWithKeys(fn ($item) => [$item->key => $item->value])
      ->toArray();
  }

  public function getOption(string $key): ?string
  {
    return Cache::remember($this->getThemeName() . $key, 1440, fn () => DB::table($this->table)
      ->whereName($this->getThemeName())
      ->whereKey($key)
      ->value('value'));
  }

  public function setOption(string $key, $value): bool
  {
    Cache::forget($this->getThemeName() . $key);

    return DB::table($this->table)
      ->updateOrInsert([
        'name' => $this->getThemeName(),
        'key' => $key,
      ], [
        'name' => $this->getThemeName(),
        'key' => $key,
        'value' => $value,
      ]);
  }
}
