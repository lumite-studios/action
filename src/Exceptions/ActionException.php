<?php

namespace LumiteStudios\Action\Exceptions;

use Exception;

class ActionException extends Exception
{
    /**
     * A "handle" method was not defined on the action.
     *
     * @return \LumiteStudios\Action\Exceptions\ActionException
     */
    public static function missingHandle(): self
    {
        return new self('There is no [handle] method.');
    }
}
