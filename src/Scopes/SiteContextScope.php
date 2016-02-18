<?php

namespace FaithPromise\Shared\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SiteContextScope implements Scope {

    protected $timezone = 'America/New_York';

    /**
     * Apply scope on the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model) {
        $column = $model->getQualifiedSiteContextColumn();
        $value = app('config')->get('site.ident');

        $builder->where(function ($query) use ($column, $value) {
            $query->whereNull($column)->orWhere($column, '=', $value);
        });

        $this->addSiteAgnostic($builder);
    }

    /**
     * Extend Builder with custom method.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    protected function addSiteAgnostic(Builder $builder) {
        $builder->macro('siteAgnostic', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }

}
