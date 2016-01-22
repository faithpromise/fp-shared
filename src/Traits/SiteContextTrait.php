<?php

namespace FaithPromise\Shared\Traits;

use FaithPromise\Shared\Scopes\SiteContextScope;

// http://softonsofa.com/laravel-5-eloquent-global-scope-how-to/
trait SiteContextTrait {

    /**
     * Boot the scope.
     *
     * @return void
     */
    public static function bootSiteContextTrait()
    {
        static::addGlobalScope(new SiteContextScope);
    }

    /**
     * Get the name of the column for applying the scope.
     *
     * @return string
     */
    public function getSiteContextColumn()
    {
        return defined('static::SITE_CONTEXT_COLUMN') ? constant('static::SITE_CONTEXT_COLUMN') : 'site';
    }

    /**
     * Get the fully qualified column name for applying the scope.
     *
     * @return string
     */
    public function getQualifiedSiteContextColumn()
    {
        return $this->getTable().'.'.$this->getSiteContextColumn();
    }

    /**
     * Get the query builder without the scope applied.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function siteAgnostic()
    {
        $instance = (new static)->newQueryWithoutScope(new SiteContextScope);
        return $instance;
    }

}
