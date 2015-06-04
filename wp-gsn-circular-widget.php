<?php
/**
Plugin Name: WP GSN Circular Widget
Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
Description: This describes my plugin in a short sentence
Version:     1.5
Author:      @ErikMattheis for Grocery Shopping Network
Author URI:  http://groceryshopping.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Sorry, nothing to see here!' );

/* intercept paths we need to use 

function gsn_circular_widget_load_spa() {
    
  if ( preg_match( '/^\/circular(.*)/i', $_SERVER["REQUEST_URI"] ) ) {
    require( dirname( __FILE__ ) . '/spa.php');
  }
}

add_action( 'parse_request', 'gsn_circular_widget_load_spa');
*/

function print_gsn_circular() {
    if (!empty(get_option('gsn_chain_id'))) {
        require( dirname( __FILE__ ) . '/spa.php');
    }
    else {
        echo '<div style="background:white; color:red"><p><strong>Please <a href="' . admin_url( 'admin.php?page=wp-gsn-circular-widget%2Fwp-gsn-circular-widget.php') . '"> enter store id</a>.</strong></p></div>';
    }
}

add_shortcode( 'gsn_circular', 'print_gsn_circular' );

/* Header modifications */
function gsn_circular_widget_enqueue_scripts () {
  if(!is_admin()) {
    wp_register_script("gsncore", "http://cdn-staging.gsngrocers.com/script/gsncore/latest/gsncore.js", FALSE, "0", TRUE);
    wp_enqueue_script("gsncore");
      /*
    wp_register_script("gmodal", "https://rawgit.com/niiknow/gmodal/master/gmodal.min.js", FALSE, "0", TRUE);
    wp_enqueue_script("gmodal");
    
    wp_register_script("angular", "https://oss.maxcdn.com/angularjs/1.2.7/angular.min.js", FALSE, "1.2.7", TRUE);
    wp_enqueue_script("angular");
    wp_register_script("sitecontentscript", "https://clientapix.gsn2.com/api/v1/store/sitecontentscript/75", FALSE, "1.2.7", TRUE);
    wp_enqueue_script("sitecontentscript");
    wp_register_script("gsncore", "http://cdn.gsngrocers.com/script/gsncore/1.4.15/gsncore.js", FALSE, "1.2.7", TRUE);
    wp_enqueue_script("gsncore");
    wp_register_script("storeApp", plugins_url("/storeApp.js", __FILE__), FALSE, "1.2.7", TRUE);
    wp_enqueue_script("storeApp");
    wp_register_script("ctrlCircular", plugins_url("/ctrlCircular.js", __FILE__), FALSE, "1.2.7", TRUE);
    wp_enqueue_script("ctrlCircular");
    */
  }
}
add_action("wp_enqueue_scripts", "gsn_circular_widget_enqueue_scripts");

/* Admin modifications */


function gsn_circular_widget_menu() {
  add_menu_page('GSN Circular Widget Settings',
    'GSN Circular Widget Settings',
    'administrator',
    __FILE__,
    'gsn_circular_widget_settings_page',
    plugins_url('/document-icon.png', __FILE__));
  add_action( 'admin_init', 'gsn_circular_widget_register_mysettings' );
}
add_action("admin_menu", "gsn_circular_widget_menu");

function gsn_circular_widget_register_mysettings() {
  register_setting( 'gsn_circular_widget', 'gsn_chain_id' );
}

function gsn_circular_widget_settings_page() {

?>

    <div class="wrap">
    <h2>GSN Circular Widget Options</h2>

    <form method="post" action="options.php">

        <table class="form-table">
            <?php settings_fields('gsn_circular_widget' ); ?>
            <?php do_settings_sections('gsn_circular_widget'); ?>
            <tr valign="top">
            <th scope="row">Chain ID</th>
            <td><input type="text" name="gsn_chain_id" value="<?php echo get_option('gsn_chain_id'); ?>" /></td>
            </tr>
        </table>
        
        <?php submit_button(); ?>

    </form>
    </div>
<?php
}


