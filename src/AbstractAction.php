<?php
namespace LumiteStudios\Action;

use LumiteStudios\Action\Interfaces\EditInterface;
use LumiteStudios\Action\Interfaces\SaveInterface;
use LumiteStudios\Action\Concerns\HasValidatorTrait;
use LumiteStudios\Action\Interfaces\CreateInterface;
use LumiteStudios\Action\Interfaces\DeleteInterface;

abstract class AbstractAction
{
	use HasValidatorTrait;

	/**
	 * An array of attributes to use.
	 * @var array<mixed>
	 */
	public array $data;

	/**
	 * An array of parameters to ignore.
	 * @var array<string>
	 */
	protected array $ignore = [
		'_token',
		'_method',
	];

	/**
	 * Create a new action instance.
	 *
	 * @param array<mixed> $data 	An array of data to use.
	 * @return void
	 */
	public function __construct(array $data = [])
	{
		$this->data = $data;

		if($this->hasMethod('authorize')) {
			$this->resolveRequest();
		}

		$this->createValidator();

		if($this->fails()) {
			$this->failedValidation();
		}
	}

	/**
	 * Handle the action.
	 *
	 * @return \LumiteStudios\Action\AbstractAction
	 */
	public function handle(): AbstractAction
	{
		if($this->hasMethod('errors')) {
			$this->resolveErrors();
		}

		if($this->fails()) {
			$this->failedValidation();
		}

		return $this;
	}

	/**
	 * Run the action.
	 *
	 * @return mixed
	 */
	public function run(): mixed
	{
		if($this instanceof CreateInterface) {
			return $this->create($this->getValidated());
		} elseif($this instanceof DeleteInterface) {
			return $this->delete($this->getValidated());
		} elseif($this instanceof EditInterface) {
			return $this->edit($this->getValidated());
		} elseif($this instanceof SaveInterface) {
			return $this->save($this->getValidated());
		}
	}

	/**
	 * Get the request data.
	 *
	 * @return array<mixed>
	 */
	protected function getData(): array
	{
		return array_merge(request()->except($this->ignore), $this->data, (request()->route()->parameters ?? []));
	}

	/**
	 * Get the validated data.
	 *
	 * @return array<mixed>
	 */
	protected function getValidated(): array
	{
		if($this->hasMethod('validated')) {
			return $this->validated();
		}
		return $this->getData();
	}

	/**
	 * Check if a method exists.
	 *
	 * @param string $method
	 * @return bool
	 */
	protected function hasMethod(string $method): bool
	{
		return method_exists($this, $method);
	}
}
