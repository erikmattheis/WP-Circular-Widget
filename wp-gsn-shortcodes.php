<?php

/*
Plugin Name: WP GSN Circular Shortcodes
Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
Description: This describes my plugin in a short sentence
Version:     1.5
Author:      @ErikMattheis for Grocery Shopping Network
Author URI:  http://groceryshopping.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/* shortcodes */
function print_gsn_login() {
    if (!empty(get_option('gsn_chain_id'))) {
        require(dirname( __FILE__ ) . '/html/login_form.php');
    }
    else {
        printMissingChainIdError();
    }
}

add_shortcode( 'gsn_login', 'print_gsn_login' );

function print_gsn_shopping_list() {
    if (!empty(get_option('gsn_chain_id'))) {
        require(dirname( __FILE__ ) . '/html/shopping_list.php');
    }
    else {
        printMissingChainIdError();
    }
}

add_shortcode('gsn_shopping_list', 'print_gsn_shopping_list');

function print_gsn_circular() {
    require(dirname( __FILE__ ) . '/html/spa.php');
}

add_shortcode( 'gsn_spa', 'print_gsn_circular' );

function printMissingChainIdError() {
    echo '<div class="error">'
    . '<p>'
    . '<strong>'
    . 'Please <a href="'
    . admin_url( 'options-general.php?page=gsn_shortcodes')
    . '">'
    . ' enter store id'
    . '</a>'
    . '</strong>'
    . '</p>'
    . '</div>';
}

/* admin changes */

function gsn_shortcodes_is_unconfigured() {

	if ($_GET["page"] === "gsn_shortcodes") {
		return;
	}

	global $pages_to_create;
	$configured = true;

	foreach($pages_to_create as $page_to_create) {
		dumpError($page_to_create["path"]);
        if (!path_exists($page_to_create["path"])) {
            $configured = false;
            break;
        }
    }

	if ($configured) {
		foreach($pages_to_create as $page_to_create) {
	        $page = get_page_or_post_by_path($page_to_create["path"]);
        	if (isset($page->post_content)
        		&& !string_contains_gsn_shortcode($page->post_content)) {
            	$configured = false;
           	}
      	}
	}

	if (!$configured) {
	    echo '<div class="error">'
		    . '<p>'
		    . 'You have not yet configured the GSN Shortcodes Plugin. To set up the plugin, go to the '
		    . '<a class="button button-primary" style="margin:0.25em 1em" href="'
		    . admin_url( 'options-general.php?page=gsn_shortcodes')
		    . '">settings page</a>'
		    . '</p>'
		    . '</div>';
	}
}

add_action("admin_notices", "gsn_shortcodes_is_unconfigured");

function gsn_shortcodes_register_settings() {
	register_setting("gsn_shortcodes", "gsn_chain_id");
}

function gsn_shortcodes_settings_link() {
	add_options_page("GSN Shortcodes Settings",
		"GSN Shortcodes Settings",
		"administrator",
		"gsn_shortcodes",
		"gsn_shortcodes_settings_page"
	);
	add_action('admin_init', 'gsn_shortcodes_register_settings');
}

add_action("admin_menu", "gsn_shortcodes_settings_link");

function gsn_shortcodes_settings_page() {
	?>
	<div class="wrap">
	<h2>GSN Shortcodes Options</h2>

	<form method="post" action="options.php">

	<table class="form-table">
	<?php settings_fields("gsn_shortcodes"); ?>
	<?php do_settings_sections("gsn_shortcodes"); ?>
	<tr valign="top">
	<th scope="row">Chain ID</th>
	<td><input type="text" name="gsn_chain_id" value="<?php echo get_option("gsn_chain_id"); ?>" /></td>
	</tr>
	</table>

	<?php submit_button(); ?>

	</form>
	</div>
<?php
}

/* create pages with pre-inserted shortcodes */

function path_exists($path) {
    return !empty(get_page_or_post_by_path($path)) || url_to_postid($path);
}

function check_for_path_conflicts() {

	global $pages_to_create;

    $path_conflict_string = "";

    foreach($pages_to_create as $page_to_create) {
        if (path_exists($page_to_create["path"])) {
            $path_conflict_string .= assemble_path_conflict_html($page_to_create["path"]);
        }
    }

    if (!empty($path_conflict_string)) {
        echo '<div class="error">'
        . '<p>'
        . '<strong>'
        . 'The following pages use paths that will conflict with GSN Shortcodes. Please rename the paths or manually add the [gsn_spa] shortcode to the content before continuing.'
        . '</strong>'
        . '<ul>'
        . $path_conflict_string
        . '</ul>'
        . '</p>'
        . '</div>';
    }
    else if ($_GET["page"] === "gsn_shortcodes") {
        create_path_pages($pages_to_create);
    }
}

add_action('admin_init', 'check_for_path_conflicts' );

function dumpError($error) {
	    echo "<div class='error'>";
    	var_dump($error);
    	echo "</div>";

}
function assemble_path_conflict_html($path) {

    $edit_post_link = "";
    $title = "";

    $page = get_page_or_post_by_path($path);
	
    if (!isset($page->post_content)
    	|| string_contains_gsn_shortcode($page->post_content)) {
        return "";
    }

	$title = $page->post_title;
	$edit_post_link = get_edit_post_link($page->id);

    $edit_post_link = '<li><a href="'
        . $edit_post_link
        . '">'
        . $title
        . '</a></li>';

    return $edit_post_link;

}

function get_page_or_post_by_path($path) {

	$page = null;

	if (!empty(get_page_by_path($path))) {
        $page = get_page_by_path($path);
    }
    else if (url_to_postid($path)) {
        $post_id = url_to_postid($path);
        $page = get_post($post_id);
    }

    return $page;

}


function string_contains_gsn_shortcode($str) {

    $pattern = get_shortcode_regex();

    if (preg_match_all( "/". $pattern ."/s", $str, $matches )
        && array_key_exists( 2, $matches )
        && (    in_array("gsn_login", $matches[2])
            ||  in_array("gsn_shopping_list", $matches[2])
            ||  in_array("gsn_spa", $matches[2]))) {
        return true; 
    }
    return false;
}

/*
function get_parent_post_id($path) {

    if (substr($path, 0, 1) !== "/") {
        trigger_error("The value of a path in the \$pages_to_create array begins with a charachter other than \"/\". This is an error with the plugin.", E_USER_ERROR);
        return false;
    }

    $parent_post_id = 0;
    $parent = explode("/", untrailingslashit($path));

    if (count($parent) > 3) {
        trigger_error("The value of a path in the $pages_to_create array is deeper than can be accommodated by the GSN Shortcodes plugin. This is an error with the plugin.", E_USER_ERROR);
        return false;
    }

    if (count($parent) === 3) {
        $parent_page = get_page_or_post_by_path("/" . $parent[2]);
    }

    return $parent_post_id;
}
*/
function create_path_pages($pages_to_create) {
	
    $links_to_created_pages_html = "";

    foreach($pages_to_create as $page_to_create) {
        if (!path_exists($page_to_create["path"])) {
            $page['post_content'] = "[gsn_spa]";
            $page['post_author'] = get_current_user_id();
            $page['post_status'] = 'publish';
            $page['post_type'] = 'page';
            $page['post_parent'] = 0; //get_parent_post_id($page_to_create["path"]);
            $page['post_title'] = $page_to_create["title"];
            $page['post_name'] = $page_to_create["path"];

            $page_id = wp_insert_post($page);
            if ($pageid === 0) {
                echo "failed to create " . $tem["title"] . " <br>";
            }
            else {
                $links_to_created_pages_html .= '<li><a href="'
                . get_permalink($page_id)
                . '">'
                . $page['post_title']
                . '</a></li>';
            }
        }
    }

    if (!empty($links_to_created_pages_html)) {
        echo '<div class="updated">'
            . '<p>GSN Shortcodes created the following pages:</p>'
            . '<ul>'
            . $links_to_created_pages_html
            . '</ul>'
            . '</div>';
     }
     else {
        echo '<div class="updated">'
            . '<p>All pages and shortcodes have validated.</p>'
            . '</div>';
     }
     
}

$pages_to_create = array(
    array(
        "path" => "/article",
        "title" => "Articles",
    ),
    array(
        "path" => "/article/featured",
        "title" => "Articles",
    ),
    array(
        "path" => "/circular",
        "title" => "Circular",
    ),
    array(
        "path" => "/not",
        "title" => "Not",
    ),
    array(
        "path" => "/contactus",
        "title" => "Contact Us",
    ),
);

