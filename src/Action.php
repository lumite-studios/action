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
	 * Create a new action instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->createValidator();

		// if the action is using the HandleRequest trait
		if($this->hasMethod('authorize')) {
			$this->resolveRequest();
		}

		// if the action is using the HandleErrors trait
		if($this->hasMethod('errors')) {
			$this->resolveErrors();
		}

		if($this->fails()) {
			$this->failedValidation();
		}

		$this->handle();
	}

	/**
	 * Handle the action.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if($this instanceof ICreateInterface) {
			return $this->create($this->getAttributes());
		} elseif($this instanceof IDeleteInterface) {
			return $this->delete($this->getAttributes());
		} elseif($this instanceof IEditInterface) {
			return $this->edit($this->getAttributes());
		} elseif($this instanceof ISaveInterface) {
			return $this->save($this->getAttributes());
		}
	}

	protected function getAllRequestData(): array
	{
		return array_merge(request()->all(), (request()->route()->parameters ?? []));
	}

	protected function getAttributes(): array
	{
		if($this->hasMethod('validated')) {
			return $this->validated();
		}
		return $this->getAllRequestData();
	}

	protected function hasMethod(string $method): bool
	{
		return method_exists($this, $method);
	}
}
