<?php
global $wpdb;
$table_name = $wpdb->prefix . 'user_registrations';

$numPerPage = 20;
$page = !isset($_GET['pageNum']) || $_GET['pageNum']<=0 ? 1 : intval($_GET['pageNum']);
$offset = ($page - 1)*$numPerPage;
$offset = $offset < 0 ? 0 : $offset;

$total = intval($wpdb->get_var("SELECT count(1) FROM $table_name where 1"));
$totalPage = ceil($total / $numPerPage);
$registrations = $wpdb->get_results("SELECT * FROM $table_name ORDER BY registration_date DESC limit $offset, $numPerPage");
?>

<div class="wrap">
    <h1>User Registrations</h1>
    <a href="<?php echo admin_url('admin-post.php?action=urf_export_csv'); ?>" class="button button-primary">Export as CSV</a>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registrations as $registration): ?>
                <tr>
                    <td><?php echo esc_html($registration->id); ?></td>
                    <td><?php echo esc_html($registration->name); ?></td>
                    <td><?php echo esc_html($registration->email); ?></td>
                    <td><?php echo esc_html($registration->phone); ?></td>
                    <td><?php echo esc_html($registration->registration_date); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if ($totalPage > 1): ?>
        <div>
            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                <a href="<?php echo admin_url('admin.php?page=user-registrations&pageNum='.$i); ?>" class="button button-primary"> <?php echo $i; ?> </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div> 