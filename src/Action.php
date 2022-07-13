<?php

namespace LumiteStudios\Action;

use ReflectionMethod;
use Illuminate\Support\Str;
use LumiteStudios\Action\Concerns\HasErrors;
use LumiteStudios\Action\Concerns\HasAttributes;
use LumiteStudios\Action\Concerns\HasValidation;
use LumiteStudios\Action\Concerns\ActionDecorator;
use LumiteStudios\Action\Exceptions\ActionException;

class Action
{
    use ActionDecorator,
        HasAttributes,
        HasErrors,
        HasValidation;

    /**
     * The request to use for validation.
     * @var \Illuminate\Http\Request
     */
    protected \Illuminate\Http\Request $request;

    /**
     * The route that action is tied to.
     * @var \Illuminate\Routing\Route
     */
    protected ?\Illuminate\Routing\Route $route;

    /**
     * Create a new action instance.
     *
     * @return void
     *
     * @throws \LumiteStudios\Action\Exceptions\ActionException
     */
    public function __construct()
    {
        if (!$this->hasMethod('handle')) {
            throw ActionException::missingHandle();
        }

        $this->attributes = collect();
        $this->request = request();
        $this->route = request()->route() ?? null;

        $this->replaceRouteAction();
        $this->fillFromRequest();
    }

    /**
     * Run the action as a function.
     *
     * @param mixed ...$parameters
     * @return void
     */
    public function __invoke(...$parameters)
    {
        $this->fill($this->resolveArgumentOrder('handle', $parameters));
        return $this->handle(...$parameters);
    }

    /**
     * Call the action.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function callAction(string $method, array $parameters): mixed
    {
        return $this->call($method, $parameters);
    }

    /**
     * Run the action as a function.
     *
     * @param string $method
     * @param mixed ...$parameters
     * @return void
     *
     * @throws \Archwardens\Exceptions\ActionException
     */
    public function call(string $method = 'handle', ...$parameters)
    {
        if ($this->shouldValidate()) {
            $this->validate();
        }

        return $this->returnResponse($this->run($method, $parameters));
    }

    /**
     * Run the action method.
     *
     * @param string $method
     * @return mixed
     */
    protected function run(string $method, array $parameters = [])
    {
        if ($this->hasMethod($method)) {
            return $this->resolveFromRouteAndCall($method, $parameters);
        }
    }

    /**
     * Return the correct response.
     *
     * @param mixed $response
     * @return mixed
     */
    protected function returnResponse(mixed $response): mixed
    {
        if ($this->hasMethod('jsonResponse') && $this->request->expectsJson()) {
            return $this->callMethod('jsonResponse', [$response]);
        } elseif ($this->hasMethod('response') && !$this->request->expectsJson()) {
            return $this->callMethod('response', [$response]);
        }

        return $response;
    }

    /**
     * Replace the action in the route.
     *
     * @return void
     */
    private function replaceRouteAction(): void
    {
        if (!isset($this->route->action['uses'])) {
            return;
        }

        $currentMethod = Str::afterLast($this->route->action['uses'], '@');
        $newMethod = $this->getDefaultRouteMethod();

        if ($currentMethod !== '__invoke' || $currentMethod === $newMethod) {
            return;
        }

        $this->route->action['uses'] = (string) Str::of($this->route->action['uses'])
            ->beforeLast('@')
            ->append('@' . $newMethod);
    }

    /**
     * Get the default route method to set.
     *
     * @return string
     */
    private function getDefaultRouteMethod(): string
    {
        if ($this->hasMethod('asController')) {
            return 'asController';
        }

        return $this->hasMethod('handle') ? 'handle' : '__invoke';
    }

    /**
     * Resolve the class parameters.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    private function resolveFromRouteAndCall(string $method, array $parameters = []): mixed
    {
        $arguments = $this->route ? $this->route->resolveMethodDependencies(
            $this->route->parametersWithoutNulls() ?? $parameters,
            new ReflectionMethod($this, $method)
        ) : $parameters;
        $ordered = $this->resolveArgumentOrder($method, $arguments);

        return $this->{$method}(...$ordered->values());
    }

    /**
     * Resolve the order of the arguments.
     *
     * @param string $method
     * @param array $arguments
     * @return \Illuminate\Support\Collection
     */
    protected function resolveArgumentOrder(string $method, array $arguments): \Illuminate\Support\Collection
    {
        return collect((new ReflectionMethod($this, $method))->getParameters())->mapWithKeys(function ($param) use ($arguments) {
            $classname = $param->getType()->getName();
            return [$param->getName() => collect($arguments)->filter(function ($arg) use ($classname) {
                return $arg instanceof $classname;
            })->first()];
        });
    }
}
