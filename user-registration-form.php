<?php
/*
 * Plugin Name: User Registration Form
 * Plugin URI: https://github.com/suhanyujie/wp-plugin-user-reg
 * Description: contact form plugin. user registration. activity user record. handle user registration 
 * Author: [suhanyujie](https://github.com/suhanyujie)
 * Author URI: https://github.com/suhanyujie
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 0.1.2
 * Requires at least: 6.7
 * Requires PHP: 8.2
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// 定义插件常量
define('URF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('URF_PLUGIN_URL', plugin_dir_url(__FILE__));

// 加载其他文件
require_once URF_PLUGIN_DIR . 'includes/database.php';
require_once URF_PLUGIN_DIR . 'includes/form.php';
require_once URF_PLUGIN_DIR . 'includes/admin.php';
require_once URF_PLUGIN_DIR . 'includes/api.php';

// Enqueue Bootstrap CSS
function urf_enqueue_bootstrap() {
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
}
add_action('wp_enqueue_scripts', 'urf_enqueue_bootstrap');

// 注册激活和卸载钩子
register_activation_hook(__FILE__, 'urf_create_table');
register_uninstall_hook(__FILE__, 'urf_uninstall');

// 注册区块编辑器资源
function urf_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'urf-block',
        URF_PLUGIN_URL . 'blocks/registration-form/block.js',
        array('wp-blocks', 'wp-element', 'wp-editor')
    );
}
add_action('enqueue_block_editor_assets', 'urf_enqueue_block_editor_assets');

// 加载管理页面样式
function urf_admin_enqueue_scripts($hook) {
    if (strpos($hook, 'urf-forms') !== false) {
        wp_enqueue_style('urf-admin', URF_PLUGIN_URL . 'assets/css/admin.css');
    }
}
add_action('admin_enqueue_scripts', 'urf_admin_enqueue_scripts');


