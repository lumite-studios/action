<?php

namespace LumiteStudios\Action\Concerns;

use Illuminate\Support\Arr;

trait HasAttributes
{
    /**
     * An array of parameters to ignore from the request.
     * @var array<string>
     */
    protected array $ignore = [
        '_token',
        '_method',
    ];

    /**
     * The attributes to use within the action.
     * @var array
     */
    protected array $attributes = [];

    /**
     * Add attributes.
     *
     * @param array $attributes
     * @return self
     */
    public function fill(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Get the data from the request.
     *
     * @return self
     */
    public function fillFromRequest(): self
    {
        $this->attributes = Arr::except(array_merge(
            $this->attributes,
            $this->route ? $this->route->parametersWithoutNulls() : [],
            $this->request->all(),
        ), $this->ignore);

        return $this;
    }

    /**
     * Get all of the attributes.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->attributes;
    }

    /**
     * Exclude keys from the attributes.
     *
     * @param array $keys
     * @return array
     */
    public function except(array $keys): array
    {
        return Arr::except($this->attributes, $keys);
    }

    /**
     * Get an attribute.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * Magic method to get an attribute.
     *
     * @param $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }
}
