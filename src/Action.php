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

		$this->createValidator();

		// if the action is using the HandleRequest trait
		if($this->hasMethod('authorize')) {
			$this->resolveRequest();
		}
	}

	/**
	 * Handle the action.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		// if the action is using the HandleErrors trait
		if($this->hasMethod('errors')) {
			$this->resolveErrors();
		}

		if($this->fails()) {
			return $this->failedValidation();
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

	protected function getData(): array
	{
		return array_merge(request()->all(), $this->data, (request()->route()->parameters ?? []));
	}

	protected function getValidated(): array
	{
		if($this->hasMethod('validated')) {
			return $this->validated();
		}
		return $this->getData();
	}

	protected function hasMethod(string $method): bool
	{
		return method_exists($this, $method);
	}
}
