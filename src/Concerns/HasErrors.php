<?php

namespace LumiteStudios\Action\Concerns;

use Illuminate\Support\MessageBag;

trait HasErrors
{
    /**
     * Errors sub group.
     * @var null|string
     */
    public $errorsGroup = null;

    /**
     * The bag to hold the errors.
     * @var \Illuminate\Support\MessageBag
     */
    public MessageBag $errorBag;

    /**
     * Resolve the errors.
     *
     * @return mixed
     */
    protected function resolveErrors(array $parameters = [])
    {
        $this->errorBag = new MessageBag();

        $this->errors($parameters);

        if ($this->hasErrors()) {
            if ($this->errorsGroup !== null) {
                return $this->failedValidation([$this->errorsGroup => $this->errorBag->getMessages()]);
            }
            return $this->failedValidation($this->errorBag->getMessages());
        }
    }

    /**
     * Add an error to the message bag.
     *
     * @param string $field
     * @param string $message
     * @return void
     */
    protected function addError(string $field, string $message): void
    {
        $this->errorBag->add($field, $message);
    }

    /**
     * Check if there are any errors.
     *
     * @return bool
     */
    protected function hasErrors(): bool
    {
        return $this->errorBag->count() > 0;
    }
}
