<?php

namespace LumiteStudios\Action\Concerns;

use Illuminate\Support\Collection;

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
    protected Collection $attributes;

    /**
     * Add attributes.
     *
     * @param array $attributes
     * @return self
     */
    public function fill($attributes): self
    {
        if (is_array($attributes)) {
            $attributes = collect($attributes);
        }

        $this->attributes = $this->attributes->merge($attributes);

        return $this;
    }

    /**
     * Get the data from the request.
     *
     * @return self
     */
    public function fillFromRequest(): self
    {
        $fromRoute = array_merge(
            $this->route ? $this->route->parametersWithoutNulls() : [],
            $this->request->only($this->hasMethod('rules')
                ? array_keys($this->rules())
                : []
            ),
        );

        $this->fill(collect($fromRoute)->except($this->ignore));

        return $this;
    }

    /**
     * Get all of the attributes.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->attributes->all();
    }

    /**
     * Exclude keys from the attributes.
     *
     * @param array $keys
     * @return array
     */
    public function except(array $keys): array
    {
        return $this->attributes->except($keys);
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
        return $this->attributes->get($key);
    }

    /**
     * Set an attribute.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, mixed $value): mixed
    {
        return $this->attributes->put($key, $value);
    }

    /**
     * Magic method to get an attribute.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Magic method to get an attribute.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }
}
