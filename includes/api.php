<?php
if (!defined('ABSPATH')) {
    exit;
}

function urf_register_rest_routes() {
    register_rest_route('wp/v2/urf', '/forms', array(
        'methods' => 'GET',
        'callback' => 'urf_get_forms',
        'permission_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('rest_api_init', 'urf_register_rest_routes');

function urf_get_forms() {
    global $wpdb;
    $forms = $wpdb->get_results(
        "SELECT id, title, description FROM {$wpdb->prefix}user_registration_forms WHERE status = 'active'"
    );
    return $forms;
} 