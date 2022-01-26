<?php

namespace LumiteStudios\Action\Tests\Classes;

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Interfaces\DeleteInterface;

class DeleteAction extends AbstractAction implements DeleteInterface
{
    public function delete(array $attributes)
    {
        return 'delete';
    }
}
