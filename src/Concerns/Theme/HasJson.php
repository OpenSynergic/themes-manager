<?php

namespace OpenSynergic\ThemesManager\Concerns\Theme;

use OpenSynergic\ThemesManager\Helpers\Json;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasJson
{
    /**
     * @var array of cached Json objects, keyed by filename
     */
    protected $json = [];

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Handle call to __get method.
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Handle call to __set method.
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    public function getVersion()
    {
        return $this->get('version');
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->get('name');
    }

    public function getAuthor()
    {
        return $this->get('author');
    }

    /**
     * Get name in lower case.
     *
     * @return string
     */
    public function getLowerName()
    {
        return mb_strtolower($this->getName());
    }

    /**
     * Get name in studly case.
     *
     * @return string
     */
    public function getStudlyName()
    {
        return Str::studly($this->getName());
    }

    /**
     * Get name in snake case.
     *
     * @return string
     */
    public function getSnakeName()
    {
        return Str::snake($this->getName());
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param null $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Set a specific data into json file.
     *
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        return $this->json()->set($key, $value)->save();
    }

    /**
     * Get json contents from the cache, setting as needed.
     *
     * @param string $file
     */
    private function json($file = null): Json
    {
        if (null === $file) {
            $file = 'theme.json';
        }

        return Arr::get($this->json, $file, function () use ($file) {
            return $this->json[$file] = Json::make($this->getPath($file), app('files'));
        });
    }
}
