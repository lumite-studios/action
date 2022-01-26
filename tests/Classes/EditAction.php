<?php

namespace LumiteStudios\Action\Tests\Classes;

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Interfaces\EditInterface;

class EditAction extends AbstractAction implements EditInterface
{
    public function edit(array $attributes)
    {
        return 'edit';
    }
}
