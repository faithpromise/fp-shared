<?php

namespace FaithPromise\Shared\Traits;

use FaithPromise\Shared\Scopes\PublicScope;

trait PublicTrait {

    /**
     * Boot the scope.
     *
     * @return void
     */
    public static function bootPublicTrait() {
        static::addGlobalScope(new PublicScope);
    }

    /**
     * Get the name of the column for applying the scope.
     *
     * @return string
     */
    public function getPublicColumn() {
        return defined('static::PUBLIC_COLUMN') ? static::PUBLIC_COLUMN : 'public_at';
    }

    /**
     * Get the fully qualified column name for applying the scope.
     *
     * @return string
     */
    public function getQualifiedPublicColumn() {
        return $this->getTable() . '.' . $this->getPublicColumn();
    }

    /**
     * Get the query builder without the scope applied.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withPrivate() {
        $instance = (new static)->newQueryWithoutScope(new PublicScope);

        return $instance;
    }

}
