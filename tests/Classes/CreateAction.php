<?php

namespace LumiteStudios\Action\Tests\Classes;

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Interfaces\CreateInterface;

class CreateAction extends AbstractAction implements CreateInterface
{
    public function create(array $attributes)
    {
        return 'create';
    }
}
