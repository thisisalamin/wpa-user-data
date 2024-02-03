<?php
/**
 * Plugin Name: WPA User Data
 * Plugin URI: htps://github.com/thisisalamin/WPA-User-Data
 * Description: A plugin to display user data
 * Version: 1.0
 * Author: Mohamed Alamin
 * Author URI:
 * License: GPL2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function wpaud_enqueue_scripts( ) {
    $path_js = plugins_url( 'js/main.js', __FILE__ );
    $dep = array( 'jquery' );
    $ver = filemtime( plugin_dir_path( __FILE__ ) . 'js/main.js' );

    wp_enqueue_script( 'ajax-script', $path_js, $dep, $ver, true );
    wp_localize_script( 'ajax-script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}
// Enqueue Scripts
add_action('wp_enqueue_scripts','wpaud_enqueue_scripts');
add_action( 'admin_enqueue_scripts', 'wpaud_enqueue_scripts' );

function wpbootstrap_enqueue_styles() {
    wp_enqueue_style( 'bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' );
    wp_enqueue_style( 'my-style', get_template_directory_uri() . 'css/style.css');
    }
add_action('wp_enqueue_scripts', 'wpbootstrap_enqueue_styles');

// Define plugin constants
define( 'WPAUD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPAUD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Plugin Activation Hook
register_activation_hook( __FILE__, 'wpaud_activation' );
register_deactivation_hook( __FILE__, 'wpaud_deactivation' );




// Plugin Activation Function
function wpaud_activation() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $wp_udata= $table_prefix . 'udata';

    $q = "CREATE TABLE IF NOT EXISTS `$wp_udata` (
        id INT(11) NOT NULL AUTO_INCREMENT,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(255) NOT NULL,
        address VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )";

    $wpdb->query( $q );
    
    $data = array(
        array(
            'full_name' => 'Alamin Mahmud',
            'email' => 'mdalamin@gmail.com',
            'phone' => '01700000000',
            'address' => 'Dhaka, Bangladesh',
        ),
        array(
            'full_name' => 'Rifat Ahmed',
            'email' => 'rifat@hello.com',
            'phone' => '01234384279347',
            'address' => 'Narsingdi,Dhaka',
        ),
        array(
            'full_name' => 'Rabiya Begum',
            'email' => 'help@googl.com',
            'phone' => '1212918283',
            'address' => 'New York, USA',
        ),
        array(
            'full_name' => 'Monir Hossain',
            'email' => 'monir@gmail.com',
            'phone' => '017000423000',
            'address' => 'Dhaka, Bangladesh',
        ),
        array(
            'full_name' => 'John Doe',
            'email' => 'jhone@hello.com',
            'phone' => '0192384279347',
            'address' => 'Carolina USA',
        ),
        array(
            'full_name' => 'Chris Doe',
            'email' => 'help@googl.com',
            'phone' => '1291828383',
            'address' => 'New York, USA',
        ),
    );

    // Insert those data into table
    foreach ( $data as $single_data ) {
        $wpdb->insert( $wp_udata, $single_data );
    }

}
// Plugin Deactivation Function
function wpaud_deactivation() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $wp_udata= $table_prefix . 'udata';

    $wpdb->query( "TRUNCATE `$wp_udata`" );
}


// Add menu in admin panel
add_action( 'admin_menu', 'wpaud_admin_menu' );
function wpaud_admin_menu() {
    add_menu_page(
        'WPA User Data',
        'WPA User Data',
        'manage_options',
        'wpaud',
        'wpaud_admin_page',
        'dashicons-admin-users',
        6
    );

    add_submenu_page(
        'wpaud',
        'Settings',
        'Settings',
        'manage_options',
        'wpaud',
        'wpaud_admin_page'
    );

    add_submenu_page(
        'wpaud',
        'WPA User Data Add New',
        'Add New',
        'manage_options',
        'wpaud_add_new',
        'wpaud_add_new_page'
    );
}

// Admin Page
function wpaud_admin_page() {
    include WPAUD_PLUGIN_DIR . 'admin/user-data.php';
}


// Ajax Call
add_action( 'wp_ajax_wpaud_search', 'wpaud_search' );
add_action( 'wp_ajax_nopriv_wpaud_search', 'wpaud_search' );
function wpaud_search(){
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $wp_udata= $table_prefix . 'udata';
    $search_term = $_POST['search_term'];

    if(!empty($search_term)){
        $q = "SELECT * FROM `$wp_udata` WHERE `full_name` LIKE '%{$search_term}%' OR `email` LIKE '%{$search_term}%' OR `phone` LIKE '%{$search_term}%' OR `address` LIKE '%{$search_term}%'";
    }else{
        $q = "SELECT * FROM `$wp_udata`";
    }

    $results = $wpdb->get_results( $q );

    ob_start();
        foreach($results as $d):
            ?>
                <tr>
                    <td><?php echo $d->id; ?></td>
                    <td><?php echo $d->full_name; ?></td>
                    <td><?php echo $d->email; ?></td>
                    <td><?php echo $d->phone; ?></td>
                    <td><?php echo $d->address; ?></td>
                </tr>
            <?php
      endforeach;
    echo ob_get_clean();
}


// Add shortcode for table
add_shortcode( 'wpaud_table', 'wpaud_ajax_table' );
function wpaud_ajax_table(){
    include WPAUD_PLUGIN_DIR . 'admin/user-data.php';
}

// Register Custom Post Type
function wpaud_cpt(){
    register_post_type( 'wpaud',
        array(
            'labels' => array(
                'name' => __( 'Cars' ),
                'singular_name' => __( 'Car' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'cars'),
            'show_in_rest'=> true,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'comments' , 'author', 'excerpt'),
            'taxonomies' => array('car-type', 'post_tag'),
            'publicly_queryable' => true,

        )
    );
}

add_action( 'init', 'wpaud_cpt' );

function register_car_type(){
    register_taxonomy(
        'car-type',
        'wpaud',
        array(
            'label' => __( 'Car Type' ),
            'rewrite' => array( 'slug' => 'car-type' ),
            'hierarchical' => true,
            'show_in_rest' => true,
        ),
    );
}

add_action( 'init', 'register_car_type' );


// Create Register Form Shortcode
add_shortcode( 'wpaud_form', 'wpaud_register_form' );
function wpaud_register_form(){
    ob_start();
    include WPAUD_PLUGIN_DIR . 'public/register-form.php';
    return ob_get_clean();
}


