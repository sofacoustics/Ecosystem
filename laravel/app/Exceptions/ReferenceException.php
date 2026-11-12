<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Str;
use Throwable;

class ReferenceException extends Exception
{
    protected string $reference;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        // Generate the reference immediately when the exception is born
        $this->reference = 'ERR-' . strtoupper(Str::random(8)); 
    }

    public function getReference(): string
    {
        return $this->reference;
    }
}
