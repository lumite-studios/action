<?php
namespace LumiteStudios\Action\Exceptions;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException as IValidationException;

class ValidationException extends IValidationException
{
    public $messages;

    /**
     * Create a new exception instance.
     *
     * @param \Illuminate\Support\MessageBag $messages
     * @return void
     */
    public function __construct(MessageBag $messages)
    {
        parent::__construct(null);
        $this->messages = $messages;
    }

    /**
     * Get all of the validation error messages.
     *
     * @return array
     */
    public function errors()
    {
        return $this->messages;
    }
}
