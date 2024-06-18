<?php

namespace App\Exceptions\Products;

use Exception;

class CreateProductInvalidArgumentException extends Exception
{    
    public $message;

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
        $this->message = $message;
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        return back()->withErrors($this->message);
    }
}
