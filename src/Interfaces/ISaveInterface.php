<?php
namespace LumiteStudios\Action\Interfaces;

interface ISaveInterface
{
	/**
	 * Save changes to a resource.
	 *
	 * @param array $attributes 	An array of attributes.
	 * @return mixed
	 */
	public function save(array $attributes);
}
