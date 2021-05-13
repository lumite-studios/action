<?php
namespace LumiteStudios\Action;

use Illuminate\Support\MessageBag;
use LumiteStudios\Action\Concerns\Validation;
use LumiteStudios\Action\Concerns\Authorization;
use LumiteStudios\Action\Interfaces\IEditInterface;
use LumiteStudios\Action\Interfaces\ISaveInterface;
use LumiteStudios\Action\Interfaces\ICreateInterface;
use LumiteStudios\Action\Interfaces\IDeleteInterface;
use LumiteStudios\Action\Interfaces\IRequestInterface;
use LumiteStudios\Action\Exceptions\ValidationException;
use Illuminate\Validation\ValidationException as IValidationException;

abstract class Action
{
	use Authorization;
	use Validation;

	/**
	 * An array of errors.
	 * @var \Illuminate\Support\MessageBag
	 */
	private $errors;

	/**
	 * Create a new action instance.
     *
     * @param array $attributes
	 */
	public function __construct(array $attributes = null)
	{
		$this->clearErrors();

		// check if this action is using the request interface
		if($this instanceof IRequestInterface && (
			!app()->runningInConsole() || env('APP_ENV') === 'testing' || config('app.env') === 'testing'
		)) {
			$this->resolveAuthorization();
			$this->resolveValidation($attributes);
		} else {
			$this->resolveAction($attributes);
		}
	}

	/**
	 * Add an error to the message bag.
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function addError(string $key, string $value)
	{
		$this->errors->add($key, $value);
	}

	/**
	 * Clear all errors.
	 *
	 * @return void
	 */
	public function clearErrors(): void
	{
		$this->errors = new MessageBag();
	}

	/**
	 * Handle a failed validation.
	 *
	 * @return redirect
	 */
    public function failedValidation()
    {
        throw new ValidationException($this->getErrors());
	}

	/**
	 * Get the message bag of errors.
	 *
	 * @return \Illuminate\Support\MessageBag
	 */
	public function getErrors(): MessageBag
	{
		return $this->errors;
	}

	/**
	 * Handle the action.
	 *
	 * @param boolean $handleFail 	Whether to handle failed validation.
	 * @return mixed
	 */
	public function handle(bool $handleFail = true)
	{
        if($this instanceof IRequestInterface && !$this->passes() && $handleFail) {
			return $this->failedValidation();
		}

		if($this instanceof ICreateInterface) {
			return $this->create($this->validated());
		} elseif($this instanceof IDeleteInterface) {
			return $this->delete($this->validated());
		} elseif($this instanceof IEditInterface) {
			return $this->edit($this->validated());
		} elseif($this instanceof ISaveInterface) {
			return $this->save($this->validated());
		}
	}

	/**
	 * Get any associated errors.
	 *
	 * @param array $attributes 	An array of attributes.
	 * @return void
	 */
	abstract protected function errors(array $attributes): void;

	/**
	 * Get all of the data from the request.
	 *
	 * @return array
	 */
	private function getAllRequestData(): array
	{
		return array_merge(request()->all(), (request()->route()->parameters ?? []));
	}

	/**
	 * Resolve any errors from the action class.
	 *
	 * @param array $attributes
	 * @return void
	 */
	private function resolveAction(array $attributes = []): void
	{
		$attributes = $attributes ?? $this->getAllRequestData();
		$this->errors($attributes);
	}
}
