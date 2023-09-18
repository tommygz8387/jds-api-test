<?php

namespace App\Exceptions;

use Exception;

class ifExistException extends Exception
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'data not found.';

    /**
     * HTTP status code for the exception.
     *
     * @var int
     */
    protected $code = 404;

    /**
     * Create a new exception instance.
     *
     * @param string $message (Optional) Custom error message.
     * @param int $code (Optional) HTTP status code.
     */
    public function __construct($message = null, $code = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }

        if ($code !== null) {
            $this->code = $code;
        }

        parent::__construct($this->message, $this->code);
    }
}
