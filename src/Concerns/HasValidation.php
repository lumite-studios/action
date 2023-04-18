<?php

namespace LumiteStudios\Action\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

trait HasValidation
{
    /**
     * Errors sub group.
     * @var null|string
     */
    public string|null $errorsGroup = null;

    /**
     * Check if the request should be validated.
     *
     * @return bool
     */
    protected function shouldValidate(): bool
    {
        return $this->hasMethod('authorize')
            || $this->hasMethod('rules');
    }

    /**
     * Validate the passed data.
     *
     * @param array $attributes
     * @return self
     */
    public function validate(array $attributes = []): self
    {
        $this->fillFromRequest();
        $this->fill($attributes);

        if ($this->hasMethod('authorize')) {
            $this->resolveAuthorization();
        }

        if ($this->hasMethod('rules')) {
            $this->resolveValidation();
        }

        return $this;
    }

    /**
     * Resolve the authorization.
     *
     * @return mixed
     */
    protected function resolveAuthorization()
    {
        if (!$this->authorize()) {
            return $this->failedAuthorization();
        }
    }

    /**
     * Throw an error on fails.
     *
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException();
    }

    /**
     * Resolve the validation.
     *
     * @return mixed
     */
    protected function resolveValidation()
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            $this->fromMethod('all', [], []),
            $this->fromMethod('rules', [], []),
            $this->fromMethod('messages', [], []),
            $this->fromMethod('customAttributes', [], []),
        );

        if ($validator->fails()) {
            return $this->failedValidation($validator->errors()->getMessages());
        }
    }

    /**
     * Handle a failed validation.
     *
     * @param array $errors
     * @return mixed
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(array $errors)
    {
        if ($this->errorsGroup !== null) {
            $errors = [$this->errorsGroup => Arr::collapse($errors)];
        }

        throw ValidationException::withMessages($errors);
    }
}
