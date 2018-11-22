<?php

namespace App\Repositories\Crm;

use App\Models\Crm\RelationAddress;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class RelationAddressRepository
 * @package App\Repositories\Crm
 * @version November 8, 2018, 12:29 pm UTC
 *
 * @method RelationAddress findWithoutFail($id, $columns = ['*'])
 * @method RelationAddress find($id, $columns = ['*'])
 * @method RelationAddress first($columns = ['*'])
*/
class RelationAddressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companyid',
        'relationid',
        'name',
        'slug',
        'address',
        'address2',
        'housenumber',
        'housenumberaddition',
        'postalcode',
        'city',
        'countryid'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RelationAddress::class;
    }
}
