<?php

function image_url_raw($image_path, $format = null) {

    if ($format !== null) {
        $format_suffix = '-' . $format . '.jpg';
        $image_path = preg_replace('/(-(square|tall|wide))?\.jpg$/', $format_suffix, $image_path);
    }

    return 'http:' . config('site.cdn_url') . '/' . $image_path;
}

function image_url($viewport_width, $image_width, $image_path, $format = null) {

    // Not a local image. Return
    if ('images/' !== substr($image_path, 0, 7)) {
        return $image_path;
    }

    $image_path_parts = parse_url($image_path);
    $image_path = $image_path_parts['path'];
    $url_params = empty($image_path_parts['query']) ? [] : explode('&', $image_path_parts['query']);

    if ($format !== null) {
        $format_suffix = '-' . $format . '.${3}';
        $image_path = preg_replace('/(-(square|tall|wide))?\.(jpg|png)$/', $format_suffix, $image_path_parts['path']);
    }

    $img_url = config('site.cdn_url') . '/' . $viewport_width . '/' . $image_width . '/' . $image_path . (count($url_params) ? '?' . implode('&', $url_params) : '');

    return $img_url;
}