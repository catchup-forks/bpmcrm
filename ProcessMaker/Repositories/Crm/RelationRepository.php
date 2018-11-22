<?php

namespace ProcessMaker\Repositories\Crm;

use ProcessMaker\Models\Crm\Relation;
use ProcessMaker\Repositories\BaseRepository;

/**
 * Class RelationRepository
 * @package App\Repositories\Crm
 * @version November 8, 2018, 12:29 pm UTC
 *
 * @method Relation findWithoutFail($id, $columns = ['*'])
 * @method Relation find($id, $columns = ['*'])
 * @method Relation first($columns = ['*'])
*/
class RelationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'relationname',
        'slug'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Relation::class;
    }
}
