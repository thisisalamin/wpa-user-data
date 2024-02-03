<?php
global $wpdb;
$table_prefix = $wpdb->prefix;
$wp_udata= $table_prefix . 'udata';

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $q = "SELECT * FROM `$wp_udata` WHERE `full_name` LIKE '%$search%' OR `email` LIKE '%$search%'";
    $data = $wpdb->get_results($q);
    if($data){
        echo '<h3>Search Result</h3>';
    }else{
        echo '<h3>No Result Found</h3>';
    }
}else{
    $q = "SELECT * FROM `$wp_udata`";
    $data = $wpdb->get_results($q);
}

ob_start();
// Print data in html table
 ?>
    <div class="wrap">
        <form action="<?php echo admin_url('admin.php'); ?>" id="my-search-form">
            <input type="hidden" name="page" value="wpaud">
            <input type="text" name="search" placeholder="Search User" id="my-search-term">
            <input type="submit" value="Search" class="button button-primary">
        </form>
        <table class="table wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody id="my-table-results">
            <?php foreach($data as $d): ?>
                <tr>
                    <td><?php echo $d->id; ?></td>
                    <td><?php echo $d->full_name; ?></td>
                    <td><?php echo $d->email; ?></td>
                    <td><?php echo $d->phone; ?></td>
                    <td><?php echo $d->address; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
echo ob_get_clean();


