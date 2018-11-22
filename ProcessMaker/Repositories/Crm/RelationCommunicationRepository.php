<?php

namespace App\Repositories\Crm;

use App\Models\Crm\RelationCommunication;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class RelationCommunicationRepository
 * @package App\Repositories\Crm
 * @version November 8, 2018, 12:29 pm UTC
 *
 * @method RelationCommunication findWithoutFail($id, $columns = ['*'])
 * @method RelationCommunication find($id, $columns = ['*'])
 * @method RelationCommunication first($columns = ['*'])
*/
class RelationCommunicationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companyid',
        'relationid',
        'name',
        'slug',
        'phonenumber'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RelationCommunication::class;
    }
}
