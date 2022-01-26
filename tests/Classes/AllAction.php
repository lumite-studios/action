<?php

namespace LumiteStudios\Action\Tests\Classes;

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Concerns\HandleErrorsTrait;
use LumiteStudios\Action\Concerns\HandleRequestTrait;

class AllAction extends AbstractAction
{
    use HandleErrorsTrait;
    use HandleRequestTrait;

    protected function authorize(): bool
    {
        return true;
    }

    protected function rules(): array
    {
        return [];
    }

    protected function errors(array $attributes): void
    {
    }
}
