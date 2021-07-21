<?php
namespace LumiteStudios\Action\Concerns;

use Illuminate\Auth\Access\AuthorizationException;

trait HandleRequest
{
	/**
	 * Whether the request is authorized.
	 *
	 * @return bool
	 */
	abstract protected function authorize(): bool;

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string|array<mixed>>
	 */
	abstract protected function rules(): array;

	/**
	 * Attempt to resolve the request data.
	 *
	 * @return void
	 */
    protected function resolveRequest(): void
    {
		if(!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }
	}

	/**
	 * Check if authorization passes.
	 *
	 * @return boolean
	 */
    public function passesAuthorization(): bool
    {
        return $this->authorize();
    }

	/**
	 * Throw an error on fails.
	 *
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function failedAuthorization()
    {
        throw new AuthorizationException();
    }
}
