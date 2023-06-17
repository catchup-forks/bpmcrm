<?php

namespace App\Facades;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;
use App\Model\InputDocument;
use App\Model\Process;

/**
 * Facade for our Input Document Manager
 *
 * @package app\Facades
 * @see \app\Managers\InputDocumentManager
 *
 * @method static Paginator index(Process $process, array $options)
 * @method static InputDocument save(Process $process, array $data)
 * @method static array update(Process $process, InputDocument $inputDocument, array $data)
 * @method static boolean|null remove(InputDocument $inputDocument)
 *
 */
class InputDocumentManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'input_document.manager';
    }
}
