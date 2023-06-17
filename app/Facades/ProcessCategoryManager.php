<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for the Process File Manager
 *
 * @package app\Facades
 * @see \app\Managers\ProcessCategoryManager
 *
 * @method \Illuminate\Database\Eloquent\Collection index($filter, $start, $limit)
 * @method \app\Model\ProcessCategory store($data)
 * @method \app\Model\ProcessCategory update(\app\Model\ProcessCategory $processCategory, $data)
 * @method bool remove(\app\Model\ProcessCategory $processCategory)
 * @method array format(\app\Model\ProcessCategory $processCategory)
 * @method array formatList(\Illuminate\Database\Eloquent\Collection $processCategories)
 */
class ProcessCategoryManager extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'process_category.manager';
    }
}
