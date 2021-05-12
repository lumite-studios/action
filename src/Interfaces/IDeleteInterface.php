<?php
namespace LumiteStudios\Action\Interfaces;

interface IDeleteInterface
{
	/**
	 * Delete a resource.
	 *
	 * @param array $attributes 	An array of attributes.
	 * @return mixed
	 */
	public function delete(array $attributes);
}
