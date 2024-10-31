<?php

namespace OptimizeMore;

defined('ABSPATH') or die('No script kiddies please!');

function opm_field_setting($key = "", $default = false) {
    if (isset($_POST)) {
        if (isset($_POST['opm'][$key])) {
            return $_POST['opm'][$key];
        }
    }
    $value = opm_instance()->Settings()->get($key, $default);
    return $value;
}
 
function opm_db_field_setting($key = "", $default = false) {
    return opm_instance()->Settings()->get($key, $default);
}

function opm_array_value($data = array(), $default = false) {
    return isset($data) ? $data : $default;
}

function opm_sanitize_text_field($value) {
    if (!is_array($value)) {
        return wp_kses_post($value);
    }
    foreach ($value as $key => $array_value) {
        $value[$key] = opm_sanitize_text_field($array_value);
    }
    return $value;
}

function opm_esc_html_e($value) {
    return opm_sanitize_text_field($value);
}

function opm_removeslashes($value) {
    return stripslashes_deep($value);
}

function opm_kses($value, $callback = 'wp_kses_post') {
    if (is_array($value)) {
        foreach ($value as $index => $item) {
            $value[$index] = opm_kses($item, $callback);
        }
    } elseif (is_object($value)) {
        $object_vars = get_object_vars($value);
        foreach ($object_vars as $property_name => $property_value) {
            $value->$property_name = opm_kses($property_value, $callback);
        }
    } else {
        $value = call_user_func($callback, $value);
    }
    return $value;
}

function opm_fix_json($matches) {
    return "s:" . strlen($matches[2]) . ':"' . $matches[2] . '";';
}