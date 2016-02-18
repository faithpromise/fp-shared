<?php

namespace FaithPromise\Shared\Scopes;

use Illuminate\Database\Query\Builder as BaseBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;
use Carbon\Carbon;

// http://softonsofa.com/laravel-5-eloquent-global-scope-how-to/
class PublishedScope implements ScopeInterface {

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
