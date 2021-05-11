<?php
namespace LumiteStudios\Action\Interfaces;

interface ICreateInterface
{
	/**
	 * Create a new resource.
	 *
	 * @param array $attributes 	An array of attributes.
	 * @return mixed
	 */
	public function create(array $attributes);
}
