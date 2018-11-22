<?php

namespace App\Repositories\Crm;

use App\Models\Crm\RelationEmailaddress;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class RelationEmailaddressRepository
 * @package App\Repositories\Crm
 * @version November 8, 2018, 12:29 pm UTC
 *
 * @method RelationEmailaddress findWithoutFail($id, $columns = ['*'])
 * @method RelationEmailaddress find($id, $columns = ['*'])
 * @method RelationEmailaddress first($columns = ['*'])
*/
class RelationEmailaddressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companyid',
        'relationid',
        'name',
        'slug',
        'emailaddress',
        'linktypeid',
        'isprimary',
        'issystem'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RelationEmailaddress::class;
    }
}
