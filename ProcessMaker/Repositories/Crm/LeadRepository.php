<?php

namespace App\Repositories\Crm;

use App\Models\Crm\Lead;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class LeadRepository
 * @package App\Repositories\Crm
 * @version November 8, 2018, 12:28 pm UTC
 *
 * @method Lead findWithoutFail($id, $columns = ['*'])
 * @method Lead find($id, $columns = ['*'])
 * @method Lead first($columns = ['*'])
*/
class LeadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companyid',
        'prospectid',
        'title',
        'slug',
        'duedate'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Lead::class;
    }
}
