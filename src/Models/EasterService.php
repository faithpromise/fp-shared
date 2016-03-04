<?php

namespace FaithPromise\Shared\Models;

use FaithPromise\Shared\Scopes\EasterServiceScope;

class EasterService extends SpecialService {

    public static function boot() {
        static::addGlobalScope(new EasterServiceScope);
        parent::boot();
    }

}
