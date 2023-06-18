<?php

namespace App\Exception;

use Exception;

/**
 * Exception thrown when a connection to a not supported driver (mysql, oracle, etc.) is tried.
 *
 * @package app\Exceptions
 */
final class DatabaseConnectionTypeNotSupportedException extends Exception
{

}
