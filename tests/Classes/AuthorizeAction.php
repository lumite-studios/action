<?php

namespace LumiteStudios\Action\Tests\Classes;

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Concerns\HandleRequestTrait;

class AuthorizeAction extends AbstractAction
{
    use HandleRequestTrait;

    protected function authorize(): bool
    {
        return false;
    }

    protected function rules(): array
    {
        return [];
    }
}
