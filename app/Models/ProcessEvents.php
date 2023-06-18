<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Collection;

/**
 * Process events relationship
 *
 */
final class ProcessEvents extends Relation
{

    /**
     * Set the base constraints on the relation query.
     */
    public function addConstraints(): void
    {
        //No Constraints
    }

    /**
     * Set the constraints for an eager load of the relation.
     */
    public function addEagerConstraints(array $models): void
    {
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->parent->getStartEvents();
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param  string  $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  string  $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        foreach ($models as $model) {
            $events = collect($model->getStartEvents());
            $model->setRelation($relation, $events);
        }
        return $models;
    }
}
