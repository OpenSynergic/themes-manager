<?php

use Illuminate\Support\Facades\Route;
use OpenSynergic\ThemesManager\Controllers\ThemeAssetController;


Route::get(config('themes-manager.asset_path') . '/{theme}/{file}', ThemeAssetController::class)
  ->where('file', '.*')
  ->name('themes-manager.asset');
