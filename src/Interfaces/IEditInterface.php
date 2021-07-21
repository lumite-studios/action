<?php
namespace LumiteStudios\Action\Interfaces;

interface IEditInterface
{
	/**
	 * Edit a resource.
	 *
	 * @param array<mixed> $attributes 	An array of attributes.
	 * @return mixed
	 */
	public function edit(array $attributes);
}
