<?php
namespace LumiteStudios\Action\Concerns;

use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\MessageBag;

trait HasValidator
{
	/**
	 * An instance of the validator.
	 * @var \Illuminate\Validation\Validator
	 */
	protected Validator $validator;

	/**
	 * Create a validator instance.
	 *
	 * @return void
	 */
	public function createValidator(): void
	{
		$data = $this->hasMethod('prepareForValidation') ? array_merge($this->getAttributes(), $this->prepareForValidation()) : $this->getAttributes();
		$rules = $this->hasMethod('rules') ? $this->rules() : [];
		$messages = $this->hasMethod('messages') ? $this->messages() : [];
		$attributes = $this->hasMethod('attributes') ? $this->attributes() : [];

		$this->validator = ValidatorFacade::make($data, $rules, $messages, $attributes);
	}

	/**
	 * Handle a failed validation.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function failedValidation()
	{
		throw new ValidationException($this->validator);
	}

	/**
	 * Check if the validation rules fail.
	 *
	 * @return boolean
	 */
	public function fails(): bool
	{
		return $this->hasErrors();
	}

	/**
	 * Get the errors from the validator.
	 *
	 * @return \Illuminate\Support\MessageBag
	 */
	public function getErrors(): MessageBag
	{
		return $this->validator->errors();
	}

	/**
	 * Get the validator instance.
	 *
	 * @return \Illuminate\Validation\Validator|null
	 */
	public function getValidator(): ?Validator
	{
		return $this->validator;
	}

	/**
	 * Check if any errors exist.
	 *
	 * @return boolean
	 */
	public function hasErrors(): bool
	{
		return count($this->getErrors()) > 0;
	}

	/**
	 * Check if the validation rules pass.
	 *
	 * @return boolean
	 */
	public function passes(): bool
	{
		return !$this->hasErrors();
	}

	/**
	 * Get the validated fields.
	 *
	 * @return array
	 */
	public function validated(): array
	{
		return $this->validator->validated();
	}
}
