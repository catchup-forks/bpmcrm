<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Thrown if the element does not belong to the process.
 *
 * @package app\Exceptions
 */

final class DoesNotBelongToProcessException extends HttpException
{

    public function __construct(string $message = null, Exception $previous = null, array $headers = [], ?int $code = 0, int $statusCode = 404)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

}
