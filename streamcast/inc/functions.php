<?php

// Function To Get Meta Value
if (!function_exists('stp_get_meta')) {
    function stp_get_meta($id, $key, $default = null) {
        $value = get_post_meta($id, $key, true);
        return $value ?: $default;
    }
}