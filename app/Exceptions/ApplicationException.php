<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ApplicationException extends Exception
{
    public function __construct($message)
    {
		parent::__construct($message);
    }
}