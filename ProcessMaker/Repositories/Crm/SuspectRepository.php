<?php

namespace App\Repositories\Crm;

use App\Models\Crm\Suspect;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SuspectRepository
 * @package App\Repositories\Crm
 * @version November 8, 2018, 12:29 pm UTC
 *
 * @method Suspect findWithoutFail($id, $columns = ['*'])
 * @method Suspect find($id, $columns = ['*'])
 * @method Suspect first($columns = ['*'])
*/
class SuspectRepository extends BaseRepository
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
        return Suspect::class;
    }
}
