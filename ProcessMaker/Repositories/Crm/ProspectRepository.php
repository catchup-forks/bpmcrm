<?php

namespace App\Repositories\Crm;

use App\Models\Crm\Prospect;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ProspectRepository
 * @package App\Repositories\Crm
 * @version November 8, 2018, 12:29 pm UTC
 *
 * @method Prospect findWithoutFail($id, $columns = ['*'])
 * @method Prospect find($id, $columns = ['*'])
 * @method Prospect first($columns = ['*'])
*/
class ProspectRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companyid',
        'relationid',
        'name',
        'slug'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Prospect::class;
    }
}
