<?php
namespace LumiteStudios\Action;

use LumiteStudios\Action\Concerns\HasValidator;
use LumiteStudios\Action\Interfaces\IEditInterface;
use LumiteStudios\Action\Interfaces\ISaveInterface;
use LumiteStudios\Action\Interfaces\ICreateInterface;
use LumiteStudios\Action\Interfaces\IDeleteInterface;
use LumiteStudios\Action\Interfaces\IRequestInterface;

abstract class Action
{
	use HasValidator;

	/**
	 * An array of attributes to use.
	 * @var array
	 */
	public array $data;

	/**
	 * Create a new action instance.
	 *
	 * @param array $data 	An array of data to use.
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
	 * @return mixed
	 */
	public function handle()
	{
		if($this->hasMethod('errors')) {
			$this->resolveErrors();
		}

		if($this->fails()) {
			$this->failedValidation();
		}

		if($this instanceof ICreateInterface) {
			return $this->create($this->getValidated());
		} elseif($this instanceof IDeleteInterface) {
			return $this->delete($this->getValidated());
		} elseif($this instanceof IEditInterface) {
			return $this->edit($this->getValidated());
		} elseif($this instanceof ISaveInterface) {
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
		return array_merge(request()->except(['_token', '_method']), $this->data, (request()->route()->parameters ?? []));
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
