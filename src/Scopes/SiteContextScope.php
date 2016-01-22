<?php

namespace FaithPromise\Shared\Scopes;

use Illuminate\Database\Query\Builder as BaseBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;

// http://softonsofa.com/laravel-5-eloquent-global-scope-how-to/
class SiteContextScope implements ScopeInterface {

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
     * Remove scope from the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function remove(Builder $builder, Model $model) {
        $query = $builder->getQuery();

        $column = $model->getQualifiedSiteContextColumn();

        $bindingKey = 0;

        foreach ((array)$query->wheres as $key => $where) {
            if ($this->isSiteContextConstraint($where, $column)) {
                $this->removeWhere($query, $key);

                // Here SoftDeletingScope simply removes the where
                // but since we use Basic where (not Null type)
                // we need to get rid of the binding as well
                $this->removeBinding($query, $bindingKey);
            }

            // Check if where is either NULL or NOT NULL type,
            // if that's the case, don't increment the key
            // since there is no binding for these types
            if (!in_array($where['type'], ['Null', 'NotNull'])) $bindingKey++;
        }
    }

    /**
     * Remove scope constraint from the query.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  int $key
     * @return void
     */
    protected function removeWhere(BaseBuilder $query, $key) {
        unset($query->wheres[$key]);

        $query->wheres = array_values($query->wheres);
    }

    /**
     * Remove scope constraint from the query.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  int $key
     * @return void
     */
    protected function removeBinding(BaseBuilder $query, $key) {
        $bindings = $query->getRawBindings()['where'];

        unset($bindings[$key]);

        $query->setBindings(array_values($bindings));
    }

    /**
     * Check if given where is the scope constraint.
     *
     * @param  array $where
     * @param  string $column
     * @return boolean
     */
    protected function isSiteContextConstraint(array $where, $column) {
        $value = $value = app('config')->get('site.ident');

        $test = ($where['type'] == 'Nested'
            && $where['query']->wheres[0]['type'] == 'Null'
            && $where['query']->wheres[0]['column'] == $column
            && $where['query']->wheres[1]['type'] == 'Basic'
            && $where['query']->wheres[1]['column'] == $column
            && $where['query']->wheres[1]['operator'] == '='
            && $where['query']->wheres[1]['value'] == $value);

        return $test;
    }

    /**
     * Extend Builder with custom method.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    protected function addSiteAgnostic(Builder $builder) {
        $builder->macro('siteAgnostic', function (Builder $builder) {
            $this->remove($builder, $builder->getModel());

            return $builder;
        });
    }
}
