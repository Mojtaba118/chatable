<?php

namespace Mojtaba\Chatable\Exceptions;

use Throwable;

class ChatNotFoundException extends \Exception
{
    public function __construct($message = "Chat Not Found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}