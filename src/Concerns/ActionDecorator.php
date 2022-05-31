<?php

namespace LumiteStudios\Action\Concerns;

trait ActionDecorator
{
    /**
     * Call a method on the action.
     *
     * @param string $method
     * @param array $parameters
     * @return bool
     */
    protected function callMethod(string $method, array $parameters = [])
    {
        return call_user_func_array([$this, $method], $parameters);
    }

    /**
     * Get a method from the action.
     *
     * @param string $method
     * @param array $parameters
     * @param mixed $default
     * @return mixed
     */
    protected function fromMethod(string $method, array $parameters = [], mixed $default = null)
    {
        return $this->hasMethod($method)
            ? $this->callMethod($method, $parameters)
            : value($default);
    }

    /**
     * Check if the action has a method.
     *
     * @param string $method
     * @return bool
     */
    protected function hasMethod(string $method): bool
    {
        return method_exists($this, $method);
    }
}
