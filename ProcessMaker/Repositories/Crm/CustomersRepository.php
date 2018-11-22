<?php

namespace App\Repositories\Crm;

use App\Models\Crm\Customers;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CustomersRepository
 * @package App\Repositories\Crm
 * @version November 8, 2018, 12:28 pm UTC
 *
 * @method Customers findWithoutFail($id, $columns = ['*'])
 * @method Customers find($id, $columns = ['*'])
 * @method Customers first($columns = ['*'])
*/
class CustomersRepository extends BaseRepository
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
        return Customers::class;
    }
}
