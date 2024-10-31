<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class OpmPluginMessages {
    
    public static function queue($message, $class = '') {
        
        $default_allowed_classes = array('error', 'warning', 'success', 'info');
        $allowed_classes = apply_filters('opm_messages_allowed_classes', $default_allowed_classes);
        $default_class = apply_filters('opm_messages_default_class', 'success');

        if (!in_array($class, $allowed_classes)) {
            $class = $default_class;
        }

        $messages = maybe_unserialize(get_option('_opm_messages', array()));
        $messages[$class][] = $message;

        update_option('_opm_messages', $messages);
        
    }

    public static function show() {
        
        $group_messages = maybe_unserialize(get_option('_opm_messages'));
        
        if (!$group_messages) {
            return;
        }

        $errors = "";
        if (is_array($group_messages)) {
            foreach ($group_messages as $class => $messages) {
                $errors .= '<div class="notice opm-notice notice-' . $class . ' is-dismissible"">';
                $prev_message = '';
                foreach ($messages as $message) {
                    if( $prev_message !=  $message)
                    $errors .= '<p>' . $message . '</p>';
                    $prev_message =  $message;
                }
                $errors .= '</div>';
            }
        }

        delete_option('_opm_messages');

        print $errors;
        
    }
}

if (class_exists('OptimizeMore\opmPluginMessages') && !function_exists('opm_queue')) {
    function opm_queue($message, $class = null) {
        \OptimizeMore\OpmPluginMessages::queue($message, $class);
    }
}
add_action('admin_notices', array('OptimizeMore\OpmPluginMessages', 'show'));