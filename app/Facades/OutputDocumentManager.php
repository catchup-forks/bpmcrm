<?php

namespace App\Facades;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;
use App\Model\OutputDocument;
use App\Model\Process;

/**
 * Facade for our Output Document Manager
 *
 * @package app\Facades
 * @see \app\Managers\OutputDocumentManager
 *
 * @method static Paginator index(Process $process, array $options)
 * @method static OutputDocument save(Process $process, array $data)
 * @method static array update(Process $process, OutputDocument $outPutDocument, array $data)
 * @method static boolean|null remove(OutputDocument $outPutDocument)
 *
 */
final class OutputDocumentManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'output_document.manager';
    }
}
