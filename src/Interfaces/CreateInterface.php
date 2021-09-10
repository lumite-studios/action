<?php
namespace LumiteStudios\Action\Interfaces;

interface CreateInterface
{
	/**
	 * Create a new resource.
	 *
	 * @param array<mixed> $attributes 	An array of attributes.
	 * @return mixed
	 */
	public function create(array $attributes);
}
