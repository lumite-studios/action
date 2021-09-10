<?php
namespace LumiteStudios\Action\Concerns;

use LumiteStudios\Action\Concerns\HasValidator;

trait HandleErrorsTrait
{
	/**
	 * Get any associated errors.
	 *
	 * @param array $attributes 	An array of attributes.
	 * @return void
	 */
	abstract protected function errors(array $attributes): void;

	/**
	 * Resolve any errors from the action.
	 *
	 * @return void
	 */
	protected function resolveErrors(): void
	{
		$this->errors($this->getData());
	}

	/**
	 * Add an error to the message bag.
	 *
	 * @param string $field
	 * @param string $message
	 * @return void
	 */
	public function addError(string $field, string $message): void
	{
		$this->getErrors()->add($field, $message);
	}
}
