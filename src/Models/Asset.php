<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model {

    protected $fillable = ['path'];

    public function getUrlAttribute($viewport_size = 'xl', $image_size = 'full', $format = null) {

        $image_path = $this->path;

        if ($format !== null) {
            $format_suffix = '-' . $format . '.${3}';
            $image_path = preg_replace('/(-(square|tall|wide))?\.(jpg|png)$/', $format_suffix, $image_path);
        }

        $url = config('site.cdn_url', FP_CDN_URL) . '/' . $viewport_size . '/' . $image_size . '/' . $image_path . '?v=' . $this->file_last_modified;

        return $url;
    }

}
