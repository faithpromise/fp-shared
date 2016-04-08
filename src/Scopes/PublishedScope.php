<?php

namespace FaithPromise\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Carbon\Carbon;

class PublishedScope implements Scope {

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
        $column = $model->getQualifiedPublishedColumn();

        $builder->where(function($query) use ($column) {
            // TODO: Need to remove whereNull clause - doesn't make sense
            // publish_at null should mean not published
            $query->whereNull($column)->orWhere($column, '<', Carbon::now($this->timezone));
        });

        $this->addWithDrafts($builder);
    }

    /**
     * Extend Builder with custom method.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     */
    protected function addWithDrafts(Builder $builder)
    {
        $builder->macro('withDrafts', function(Builder $builder)
        {
            return $builder->withoutGlobalScope($this);
        });
    }

}
