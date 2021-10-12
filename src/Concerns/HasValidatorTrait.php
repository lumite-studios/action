<?php
namespace LumiteStudios\Action\Concerns;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

trait HasValidatorTrait
{
	/**
	 * An instance of the validator.
	 * @var \Illuminate\Contracts\Validation\Validator
	 */
	protected ValidatorContract $validator;

	/**
	 * Create a validator instance.
	 *
	 * @return void
	 */
	public function createValidator(): void
	{
		$data = $this->hasMethod('prepareForValidation') ? array_merge($this->getData(), $this->prepareForValidation()) : $this->getData();
		$rules = $this->hasMethod('rules') ? $this->rules() : [];
		$messages = $this->hasMethod('messages') ? $this->messages() : [];
		$attributes = $this->hasMethod('attributes') ? $this->attributes() : [];

		$this->validator = ValidatorFacade::make($data, $rules, $messages, $attributes);
	}

	/**
	 * Handle a failed validation.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 * @return void
	 */
	public function failedValidation(): void
	{
		throw new ValidationException($this->getValidator());
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
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function getValidator(): ValidatorContract
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
	 * @return array<mixed>
	 */
	public function validated(): array
	{
		return $this->validator->validated();
	}
}
