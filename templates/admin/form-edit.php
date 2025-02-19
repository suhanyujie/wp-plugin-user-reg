<?php
$form_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$form = null;
$fields = array();

if ($form_id) {
    global $wpdb;
    $form = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}user_registration_forms WHERE id = %d",
        $form_id
    ));
    if ($form) {
        $fields = json_decode($form->fields, true);
    }
}
?>

<div class="wrap">
    <h1><?php echo $form_id ? '编辑表单' : '新建表单'; ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field('urf_save_form'); ?>
        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">

        <table class="form-table">
            <tr>
                <th scope="row"><label for="title">表单标题</label></th>
                <td>
                    <input name="title" type="text" id="title" class="regular-text" 
                           value="<?php echo $form ? esc_attr($form->title) : ''; ?>" required>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="description">表单描述</label></th>
                <td>
                    <textarea name="description" id="description" class="large-text" rows="3"
                    ><?php echo $form ? esc_textarea($form->description) : ''; ?></textarea>
                </td>
            </tr>
        </table>

        <div class="urf-fields-container">
            <h2>表单字段</h2>
            <div id="urf-fields">
                <?php 
                if (!empty($fields)):
                    foreach ($fields as $index => $field):
                ?>
                    <div class="urf-field">
                        <h4>字段 #<?php echo $index + 1; ?></h4>
                        <input type="hidden" name="fields[<?php echo $index; ?>][type]" value="<?php echo esc_attr($field['type']); ?>">
                        <p>
                            <label>字段名称：</label>
                            <input type="text" name="fields[<?php echo $index; ?>][name]" 
                                   value="<?php echo esc_attr($field['name']); ?>" required>
                        </p>
                        <p>
                            <label>显示标签：</label>
                            <input type="text" name="fields[<?php echo $index; ?>][label]" 
                                   value="<?php echo esc_attr($field['label']); ?>" required>
                        </p>
                        <p>
                            <label>
                                <input type="checkbox" name="fields[<?php echo $index; ?>][required]" 
                                       <?php checked(isset($field['required']) && $field['required']); ?>>
                                必填
                            </label>
                        </p>
                        <button type="button" class="button remove-field">删除字段</button>
                    </div>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
            <button type="button" class="button" id="add-field">添加字段</button>
        </div>

        <p class="submit">
            <input type="submit" name="urf_form_save" class="button button-primary" value="保存表单">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    let fieldCount = <?php echo !empty($fields) ? count($fields) : 0; ?>;

    $('#add-field').on('click', function() {
        const template = `
            <div class="urf-field">
                <h4>字段 #${fieldCount + 1}</h4>
                <input type="hidden" name="fields[${fieldCount}][type]" value="text">
                <p>
                    <label>字段名称：</label>
                    <input type="text" name="fields[${fieldCount}][name]" required>
                </p>
                <p>
                    <label>显示标签：</label>
                    <input type="text" name="fields[${fieldCount}][label]" required>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="fields[${fieldCount}][required]">
                        必填
                    </label>
                </p>
                <button type="button" class="button remove-field">删除字段</button>
            </div>
        `;
        $('#urf-fields').append(template);
        fieldCount++;
    });

    $(document).on('click', '.remove-field', function() {
        $(this).closest('.urf-field').remove();
    });
});
</script>

<style>
.urf-field {
    background: #f9f9f9;
    padding: 15px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
}
.urf-fields-container {
    margin: 20px 0;
}
.urf-field h4 {
    margin-top: 0;
}
</style> 