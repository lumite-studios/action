<?php

namespace LumiteStudios\Action\Tests\Classes;

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Concerns\HandleRequestTrait;

class RuleAction extends AbstractAction
{
    use HandleRequestTrait;

    protected function authorize(): bool
    {
        return true;
    }

    protected function rules(): array
    {
        return [
            'username' => ['required'],
        ];
    }
}
