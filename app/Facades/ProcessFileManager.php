<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for the Process File Manager
 *
 * @package app\Facades
 * @see \app\Managers\ProcessFileManager
 *
 * @method array read($path, \app\Model\Process $process, $getContents = false)
 * @method array store(\app\Model\Process $process, User $user, $data)
 * @method array update(array $data, \app\Model\Process $process, \app\Model\ProcessFile $processFile, User $user)
 * @method void remove(\app\Model\Process $process, \app\Model\ProcessFile $processFile, $verifyingRelationship = true)
 * @method void removeFolder($path, \app\Model\Process $process)
 * @method array format(\app\Model\ProcessFile $processFile, $includeContent = false, $editableAsString = false)
 * @method mixed putUploadedFileIntoProcessFile(UploadedFile $file, ProcessFile $processFile)
 */
final class ProcessFileManager extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'process_file.manager';
    }
}
