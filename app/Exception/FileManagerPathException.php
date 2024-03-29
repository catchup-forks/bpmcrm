<?php

namespace App\Exception;

use Exception;
use Throwable;

/**
 * Invalid File Manager path exception.
 *
 */
final class FileManagerPathException extends Exception
{

    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = G::LoadTranslation("ID_INVALID_PRF_PATH");
        parent::__construct($message, $code, $previous);
    }
}
