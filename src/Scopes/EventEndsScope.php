<?php

namespace FaithPromise\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Carbon\Carbon;

class EventEndsScope implements Scope {

    protected $timezone = 'America/New_York';

    /**
     * Apply scope on the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $column = $model->getQualifiedEventEndsColumn();

        $builder->where(function($query) use ($column) {
            $query->whereNull($column)->orWhere($column, '>', Carbon::now($this->timezone)->endOfDay());
        });

        $this->addWithPast($builder);
    }

    /**
     * Extend Builder with custom method.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     */
    protected function addWithPast(Builder $builder)
    {
        $builder->macro('withPast', function(Builder $builder)
        {
            return $builder->withoutGlobalScope($this);
        });
    }

}
