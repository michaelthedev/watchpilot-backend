<?php
namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct($message)
    {
        response()->httpCode(400)->json([
            'status' => 'error',
            'message' => $message,
        ]);
    }
}