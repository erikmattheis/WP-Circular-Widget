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

/* shortcodes */
function print_gsn_login() {
    if (!empty(get_option('gsn_chain_id'))) {
        require( dirname( __FILE__ ) . '/login_form.php');
    }
    else {
        printMissingChainIdError();
    }
}

add_shortcode( 'gsn_login', 'print_gsn_login' );

function print_gsn_shopping_list() {
    if (!empty(get_option('gsn_chain_id'))) {
        require( dirname( __FILE__ ) . '/shopping_list.php');
    }
    else {
        printMissingChainIdError();
    }
}

add_shortcode('gsn_shopping_list', 'print_gsn_shopping_list');

function print_gsn_circular() {
    require( dirname( __FILE__ ) . '/spa.php');
}

add_shortcode( 'gsn_circular', 'print_gsn_circular' );

/* Header modifications */
function gsn_circular_widget_enqueue_scripts () {
  if(!is_admin()) {
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
    plugins_url('/images/search-hat.png', __FILE__));
  add_action( 'admin_init', 'gsn_circular_widget_register_mysettings' );
}
add_action("admin_menu", "gsn_circular_widget_menu");

function gsn_circular_widget_register_mysettings() {
  register_setting( 'gsn_circular_widget', 'title' );
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

/* Widget */

class GSN_Circular_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            "gsn_circular_widget", // widget Id
            "GSN Circular Widget", // widget name
            array( 'description' => "Displays circular.")
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        ?>

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <style type="text/css">
    
        .gmodal {
            /* cross-browser IE8 and up compatible data URI RGBA(0,0,0,0.7) */
            background: url("data:image/gif;base64, iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNg2AQAALUAs0e+XlcAAAAASUVORK5CYII=");
        }
    
        .myModalContent {
            position: relative;
            background: #fff;
            width: 500px;
            padding: 10px;
        }
    
        .myCloseButton {
            position: absolute;
            top: 5px;
            right: 5px;
        }
    
        /* make bootstrap modal scrollable */

        .modal-dialog {
          width: 100%;
          height: 100%;
          padding: 0;
        }

        .modal-content {
          height: 100%;
          border-radius: 0;
        }

        .modal-body {
            /*max-height: 200px;*/
            overflow-y: auto;
        }
        .gmodal-content {
            opacity: 0;
            -webkit-transition: opacity 1s linear;
            transition: opacity 1s linear;
        }
        .gmodal-content.in {
            opacity: 1;
        }
    </style>
    <!-- /For demo purpose -->

    <script type="text/javascript">
        function showGSNCircularModal() {
            // you don't need no stinking jquery
            gmodal.show({content: document.getElementById('gsn-circular-widget-modal-content').innerHTML, hideOn: 'click,esc'});
        }
    </script>

        <div>
            
        <?php

        if (!empty($instance[ "title" ])) {
            echo $args["before_title"]
              . $instance[ "title" ]
              . $args["after_title"];
        }

        ?>

        <button onclick="showGSNCircularModal()">View Circular</button>

        </div>

    <script type="text/html" id="gsn-circular-widget-modal-content">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close gmodal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
              </div>
              <div class="modal-body">
                <?php require("modal_content.php"); ?>
                </div>
            </div>
        </div>
    </script>

        <?php
        echo $args["after_widget"];
    }

    public function form( $instance ) {

        if ( isset( $instance[ "title" ] ) ) {
            $title = $instance[ "title" ];
        }
        else {
            $title = "";
        }

        echo '<p><label for="'
            . $this->get_field_id( 'title' )
            . '">Title</label>'
            . '<input class="widefat" id="'
            . $this->get_field_id( 'title' )
            . '" name="'
            . $this->get_field_name( 'title' )
            . '" type="text" value="'
            . esc_attr( $title )
            . '"></p>';
    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();
        $instance["title"] = ( ! empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"] ) : "";

        return $instance;
    }
}

function register_GSN_Circular_Widget() {
    register_widget( 'gsn_circular_widget' );
}

add_action( 'widgets_init', 'register_GSN_Circular_Widget' );

