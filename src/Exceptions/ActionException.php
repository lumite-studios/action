<?php

namespace LumiteStudios\Action\Exceptions;

use Exception;

class ActionException extends Exception
{
    /**
     * A "handle" method was not defined on the action.
     *
     * @return \Archwardens\Exceptions\ActionException
     */
    public static function missingHandle(): \Archwardens\Exceptions\ActionException
    {
        return new self('There is no [handle] method.');
    }
}
