<?php
if (!defined('ABSPATH')) {
    exit;
}

// Create the database table on plugin activation
function urf_create_table() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // 注册表
    $table_name = $wpdb->prefix . 'user_registrations';
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        phone varchar(20) NOT NULL,
        form_id mediumint(9) NOT NULL,
        registration_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // 表单模板表
    $form_table = $wpdb->prefix . 'user_registration_forms';
    $form_sql = "CREATE TABLE $form_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        description text,
        fields text NOT NULL,
        status varchar(20) DEFAULT 'active',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($form_sql);

    // 添加默认表单
    $default_form = array(
        'title' => 'default form',
        'description' => 'basic form',
        'fields' => json_encode([
            [
                'type' => 'text',
                'name' => 'name',
                'label' => '名前',
                'required' => true
            ],
            [
                'type' => 'email',
                'name' => 'email',
                'label' => 'メール',
                'required' => true
            ],
            [
                'type' => 'tel',
                'name' => 'phone',
                'label' => '電話番号',
                'required' => true
            ]
        ])
    );

    $wpdb->insert($form_table, $default_form);
}

// Delete the database table on plugin uninstall
function urf_uninstall() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_registrations';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $form_table = $wpdb->prefix . 'user_registration_forms';
    $wpdb->query("DROP TABLE IF EXISTS $form_table");
} 