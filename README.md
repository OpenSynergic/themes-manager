# Themes Manager

## Installation

You can install the package via composer:

```
  composer require opensynergic/themes-manager
```

## Usage

### Assets

A theme can have its own assets (images, stylesheets, javascript, ...). Theme's specific assets should be on `public` folder inside their own theme.

To generate an asset url you can use:

```php

Themes::asset('css/app.min.css');

// or

theme_asset('css/app.min.css');

// or if you were inside OpenSynergic\ThemesManager\Theme object
$this->asset('css/app.min.css');
```

This will generate a url asset from current active theme:

```
http://localhost/themes/themeName/css/app.min.css
```

## Artisan Command

### Generate new theme.

You can easily create a new Theme by using the following command and follow the steps:

```
  php artisan themes-manager:make themeName
```

This command will create a new Theme directory with all necessary files within the themes folder.

```
themes
   ├── themeName
   │   ├── lang
   │   ├── public
   │   ├── resources
   │   │   ├── css
   │   │   ├── js
   │   │   └── views
   │   ├── theme.php
   │   └── theme.json
   └── ...
```

### Theme List

List all existing themes in your application with their details.

```
php artisan themes-manager:list
```

### Enable Theme

To enable theme you can use the following command:

```
php artisan themes-manager:enable themeName
```

> Only one theme can enable at a time

### Clear Cache

```
php artisan themes-manager:cache:clear
```

## Configuration

### Themes Directory

Themes Manager will look for your themes into the `themes` folder by default. You can customize this with the `themes-manager.dir` configuration value.
