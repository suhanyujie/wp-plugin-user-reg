<?php
global $wpdb;
$forms = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}user_registration_forms ORDER BY id DESC");
?>

<div class="wrap">
    <h1 class="wp-heading-inline">表单管理</h1>
    <a href="<?php echo admin_url('admin.php?page=urf-forms&action=new'); ?>" class="page-title-action">添加新表单</a>

    <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
        <div class="notice notice-success is-dismissible">
            <p>表单保存成功！</p>
        </div>
    <?php endif; ?>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>标题</th>
                <th>描述</th>
                <th>字段数量</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($forms as $form): ?>
                <?php $fields = json_decode($form->fields, true); ?>
                <tr>
                    <td><?php echo esc_html($form->id); ?></td>
                    <td><?php echo esc_html($form->title); ?></td>
                    <td><?php echo esc_html($form->description); ?></td>
                    <td><?php echo count($fields); ?></td>
                    <td><?php echo esc_html($form->created_at); ?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=urf-forms&action=edit&id=' . $form->id); ?>" class="button">编辑</a>
                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=urf-forms&action=delete&id=' . $form->id), 'delete_form_' . $form->id); ?>" class="button delete" onclick="return confirm('确定要删除这个表单吗？')">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div> 