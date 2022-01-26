<?php

namespace LumiteStudios\Action\Tests\Classes;

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Concerns\HandleErrorsTrait;

class ErrorAction extends AbstractAction
{
    use HandleErrorsTrait;

    protected function errors(array $attributes): void
    {
    }
}
