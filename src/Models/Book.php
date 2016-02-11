<?php

namespace FaithPromise\Shared\Models;

use FaithPromise\Shared\Scopes\PostTypeBookScope;

class Book extends Post {

    public static function boot() {
        static::addGlobalScope(new PostTypeBookScope);
        parent::boot();
    }

}
