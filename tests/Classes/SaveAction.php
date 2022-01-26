<?php

namespace LumiteStudios\Action\Tests\Classes;

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Interfaces\SaveInterface;

class SaveAction extends AbstractAction implements SaveInterface
{
    public function save(array $attributes)
    {
        return 'save';
    }
}
