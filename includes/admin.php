<?php
if (!defined('ABSPATH')) {
    exit;
}

// Add an admin page to view registrations
function urf_admin_page() {
    add_menu_page(
        'User Registrations',
        'Registrations',
        'manage_options',
        'user-registrations',
        'urf_admin_page_content',
        'dashicons-list-view',
        6
    );
}
add_action('admin_menu', 'urf_admin_page');

// Display registrations in the admin page
function urf_admin_page_content() {
    include URF_PLUGIN_DIR . 'templates/admin-page.php';
}

// Handle CSV export
function urf_export_csv() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_registrations';
    $registrations = $wpdb->get_results("SELECT * FROM $table_name ORDER BY registration_date DESC");

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="user_registrations_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Name', 'Email', 'Phone', 'Registration Date'));

    foreach ($registrations as $registration) {
        fputcsv($output, array(
            $registration->id,
            $registration->name,
            $registration->email,
            $registration->phone,
            $registration->registration_date
        ));
    }

    fclose($output);
    exit;
}
add_action('admin_post_urf_export_csv', 'urf_export_csv');

// 添加管理菜单
function urf_admin_menu() {
    // 主菜单：注册管理
    add_menu_page(
        '注册管理',
        '注册管理',
        'manage_options',
        'user-registrations',
        'urf_registrations_page',
        'dashicons-list-view',
        6
    );

    // 子菜单：注册列表
    add_submenu_page(
        'user-registrations',
        '注册列表',
        '注册列表',
        'manage_options',
        'user-registrations',
        'urf_registrations_page'
    );

    // 子菜单：表单管理
    add_submenu_page(
        'user-registrations',
        '表单管理',
        '表单管理',
        'manage_options',
        'urf-forms',
        'urf_forms_page'
    );
}
add_action('admin_menu', 'urf_admin_menu');

// 注册列表页面
function urf_registrations_page() {
    include URF_PLUGIN_DIR . 'templates/admin/registrations.php';
}

// 表单管理页面
function urf_forms_page() {
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    
    switch ($action) {
        case 'new':
        case 'edit':
            include URF_PLUGIN_DIR . 'templates/admin/form-edit.php';
            break;
        default:
            include URF_PLUGIN_DIR . 'templates/admin/forms.php';
            break;
    }
}

// 处理表单的保存
function urf_handle_form_save() {
    if (!isset($_POST['urf_form_save'])) {
        return;
    }

    if (!current_user_can('manage_options')) {
        wp_die('未授权访问');
    }

    check_admin_referer('urf_save_form');

    global $wpdb;
    $table_name = $wpdb->prefix . 'user_registration_forms';

    $form_data = array(
        'title' => sanitize_text_field($_POST['title']),
        'description' => sanitize_textarea_field($_POST['description']),
        'fields' => json_encode(urf_sanitize_form_fields($_POST['fields']))
    );

    if (isset($_POST['form_id']) && !empty($_POST['form_id'])) {
        // 更新表单
        $wpdb->update(
            $table_name,
            $form_data,
            array('id' => intval($_POST['form_id']))
        );
    } else {
        // 新建表单
        $wpdb->insert($table_name, $form_data);
    }

    wp_redirect(admin_url('admin.php?page=urf-forms&message=success'));
    exit;
}
add_action('admin_init', 'urf_handle_form_save');

// 处理表单字段的清理
function urf_sanitize_form_fields($fields) {
    $sanitized_fields = array();
    
    foreach ($fields as $field) {
        $sanitized_fields[] = array(
            'type' => sanitize_text_field($field['type']),
            'name' => sanitize_key($field['name']),
            'label' => sanitize_text_field($field['label']),
            'required' => isset($field['required']) ? true : false
        );
    }
    
    return $sanitized_fields;
} 