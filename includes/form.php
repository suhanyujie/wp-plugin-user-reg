<?php
if (!defined('ABSPATH')) {
    exit;
}

// Add a shortcode to display the registration form
function urf_registration_form($attributes) {
    $form_id = isset($attributes['formId']) ? intval($attributes['formId']) : 1;
    
    global $wpdb;
    $form = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}user_registration_forms WHERE id = %d",
        $form_id
    ));

    if (!$form) {
        return '<div class="alert alert-danger">表单不存在</div>';
    }

    $fields = json_decode($form->fields, true);
    
    ob_start();
    include URF_PLUGIN_DIR . 'templates/dynamic-form.php';
    return ob_get_clean();
}
add_shortcode('user_registration_form', 'urf_registration_form');

// Handle form submission
function urf_handle_submission() {
    if (isset($_POST['urf_submit'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_registrations';
        $form_id = intval($_POST['urf_form_id']);
        
        // 获取表单字段
        $form = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}user_registration_forms WHERE id = %d",
            $form_id
        ));
        
        if (!$form) {
            echo '<div class="alert alert-danger mt-3">无效的表单</div>';
            return;
        }

        $fields = json_decode($form->fields, true);
        $data = array('form_id' => $form_id);
        $valid = true;

        foreach ($fields as $field) {
            $value = isset($_POST['urf_' . $field['name']]) ? $_POST['urf_' . $field['name']] : '';
            
            if ($field['required'] && empty($value)) {
                $valid = false;
                break;
            }

            switch ($field['type']) {
                case 'email':
                    $value = sanitize_email($value);
                    break;
                default:
                    $value = sanitize_text_field($value);
            }

            $data[$field['name']] = $value;
        }

        if ($valid) {
            $wpdb->insert($table_name, $data);
            echo '<div class="alert alert-success mt-3">提交成功！</div>';
        } else {
            echo '<div class="alert alert-danger mt-3">请填写所有必填字段。</div>';
        }
    }
}
add_action('init', 'urf_handle_submission');

// 注册 Gutenberg 区块
function urf_register_block() {
    // 注册区块脚本
    wp_register_script(
        'urf-block',
        URF_PLUGIN_URL . 'blocks/registration-form/block.js',
        array('wp-blocks', 'wp-element', 'wp-editor')
    );

    // 注册区块
    register_block_type('urf/registration-form', array(
        'editor_script' => 'urf-block',
        'render_callback' => 'urf_registration_form'
    ));
}
add_action('init', 'urf_register_block'); 