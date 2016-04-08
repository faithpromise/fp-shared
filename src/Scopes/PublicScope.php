<?php

namespace FaithPromise\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Carbon\Carbon;

class PublicScope implements Scope {

    protected $timezone = 'America/New_York';

    /**
     * Apply scope on the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model) {
        $column = $model->getQualifiedPublicColumn();

        $builder->where(function ($query) use ($column) {
            $query->where($column, '<', Carbon::now($this->timezone));
        });

        $this->addWithSecret($builder);
    }

    /**
     * Extend Builder with custom method.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    protected function addWithSecret(Builder $builder) {
        $builder->macro('withSecret', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }

}
