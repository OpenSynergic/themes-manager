<?php

namespace OpenSynergic\ThemesManager\Concerns\Theme;


trait HasCover
{
  public $coverImg = 'cover.jpg';

  function hasCoverImage(): bool
  {
    return file_exists($this->getCoverImagePath());
  }

  function getCoverImagePath(): string
  {
    return $this->getPublicPath($this->coverImg);
  }

  function getCoverImageUrl(): string
  {
    return $this->asset($this->coverImg);
  }
}
