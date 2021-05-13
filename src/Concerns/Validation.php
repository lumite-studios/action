<?php
namespace LumiteStudios\Action\Concerns;

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

trait Validation
{
	/**
	 * The name of the error bag to use.
	 * @var string
	 */
	protected $errorBag = 'default';

	/**
	 * The message bag instance.
	 * @var \Illuminate\Support\MessageBag
	 */
	protected $messages;

	/**
	 * An instance of the validator.
	 * @var \Illuminate\Validation\Validator
	 */
	protected $validator;

	/**
	 * Alter the data to validate.
	 *
	 * @param array $data
	 * @return void
	 */
	public function alterData(array $data = [])
	{
		$data = array_merge($this->validator->getData(), $data);
		$this->clearValidation();
		$this->resolveValidation($data);
	}

	/**
	 * Clear this validation instance.
	 *
	 * @return void
	 */
	public function clearValidation()
	{
		$this->validator = null;
	}

	public function getValidator()
	{
		return $this->validator;
	}

	/**
	 * Check if the validation rules pass.
	 *
	 * @return boolean
	 */
	public function passes(): bool
	{
		return $this->getErrors()->isEmpty();
	}

	/**
	 * Attempt to resolve the validation.
	 *
	 * @param array $data
	 * @return $this
	 */
	public function resolveValidation(array $data = null)
	{
		$this->clearValidation();
		$this->setValidatorInstance($data);
		return $this;
	}

	/**
	 * Get the validated fields.
	 *
	 * @return array
	 */
	public function validated(): array
	{
		return isset($this->validator) ? $this->validator->validated() : [];
	}

	/**
	 * Create a validator instance.
	 *
	 * @return \Illuminate\Validation\Validator
	 */
	private function createValidator(array $data = null): Validator
	{
		$data = $data ?? $this->getAllRequestData();
		$rules = method_exists($this, 'rules') ? $this->rules() : [];
		$messages = method_exists($this, 'messages') ? $this->messages() : [];
		$attributes = method_exists($this, 'attributes') ? $this->attributes() : [];

		return ValidatorFacade::make($data, $rules, $messages, $attributes);
	}

	/**
	 * Set the validator instance.
	 *
	 * @param \Illuminate\Validation\Validator $validator
	 * @return void
	 */
	private function setValidatorInstance(array $data = null)
	{
		if($this->validator === null) {
			$this->validator = $this->createValidator($data);
			$this->errors = $this->validator->errors();

			if($this->validator->passes()) {
				$this->resolveAction($this->validated());
			} else {
                $this->failedValidation();
            }
		}
	}
}
