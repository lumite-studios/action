<?php
namespace LumiteStudios\Action\Concerns;

use Illuminate\Auth\Access\AuthorizationException;

trait Authorization
{
	/**
	 * Attempt to resolve the authorization.
	 *
	 * @return \LumiteStudios\Action\Concerns\Authorization
	 */
    private function resolveAuthorization()
    {
		if(!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        return $this;
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
    protected function failedAuthorization(): AuthorizationException
    {
        throw new AuthorizationException('This action is unauthorized.');
    }
}
