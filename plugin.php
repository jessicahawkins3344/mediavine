<?php

/**
 * Plugin Name: Mediavine
 * Plugin URI:  https://github.com/jessicahawkins3344/birdmash
 * Description: Widget to track selected twitter account activities.
 * Version:     1.0
 * Author:      Jessica Hawkins
 * Author URI:  http://jsquaredcreative.com
 * License:     GPLv2
 * Text Domain: bird-mash
 *
 * @link https://github.com/jessicahawkins3344/birdmash
 *
 * @package Mediavine
 * @version 1.0
 */

define('MEDIAVINE_URL', plugin_dir_url( __FILE__ ));
define('MEDIAVINE_DIR', plugin_dir_path(__FILE__));

require MEDIAVINE_DIR . '/class.settings-api.php'; //
require MEDIAVINE_DIR . '/options.php'; //

new Metabox_Settings_API_ACP();

// Enqueue the plugins scripts & Styles
function add_meta_box_scripts() {
    wp_enqueue_style( 'meta-box-style',  plugins_url('style.css', __FILE__));
}
add_action( 'wp_enqueue_scripts', 'add_meta_box_scripts', 99 );

require MEDIAVINE_DIR . '/style.php'; //


// Creates custom meta box input fields
function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <label for="meta-box-text">Cost</label>
            <input name="meta-box-text" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-text", true); ?>">

            <br>

            <label for="meta-box-dropdown">Difficulty Level</label>
            <select name="meta-box-dropdown">
                <?php 
                    $option_values = array('', easy, moderate, difficult);

                    foreach($option_values as $key => $value) 
                    {
                        if($value == get_post_meta($object->ID, "meta-box-dropdown", true))
                        {
                            ?>
                                <option selected><?php echo $value; ?></option>
                            <?php    
                        }
                        else
                        {
                            ?>
                                <option><?php echo $value; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>

            <br>
            <label for="meta-box-dropdown-duration">Estimated Time</label>
            <select name="meta-box-dropdown-duration">
                <?php 
                    $option_values = array('', 'Less than 1 week', '2 to 4 weeks', '4+ weeks');

                    foreach($option_values as $key => $value) 
                    {
                        if($value == get_post_meta($object->ID, "meta-box-dropdown-duration", true))
                        {
                            ?>
                                <option selected><?php echo $value; ?></option>
                            <?php    
                        }
                        else
                        {
                            ?>
                                <option><?php echo $value; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
        </div>
    <?php  
}


// Save custom meta box data to post
function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    $meta_box_dropdown_value = "";

    if(isset($_POST["meta-box-text"]))
    {
        $meta_box_text_value = $_POST["meta-box-text"];
    }   
    update_post_meta($post_id, "meta-box-text", $meta_box_text_value);

    if(isset($_POST["meta-box-dropdown"]))
    {
        $meta_box_dropdown_value = $_POST["meta-box-dropdown"];
    }   
    update_post_meta($post_id, "meta-box-dropdown", $meta_box_dropdown_value);

     if(isset($_POST["meta-box-dropdown-duration"]))
    {
        $meta_box_dropdown_duration_value = $_POST["meta-box-dropdown-duration"];
    }   
    update_post_meta($post_id, "meta-box-dropdown-duration", $meta_box_dropdown_duration_value);
}

add_action("save_post", "save_custom_meta_box", 10, 2);


// Register new custom meta box
function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Custom Meta Box", "custom_meta_box_markup", "post", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");


// Display meta box data on the front end
function mv_before_after($content) {

	$mv_custom_class = my_get_option( 'custom-class', 'metabox_defaults', '' );
	
	$before = "<div class='diy " . $mv_custom_class . "'>"; 
	$after = "</div>";

	$cost_value = get_post_meta( get_the_ID(), 'meta-box-text', true );
	$cost_value_new = '';
	// Check if the custom field has a value.
	if ( ! empty( $cost_value ) ) {
	    $cost_value_new = "<div class='diy-item'>Price Estimate: $cost_value </div>";
	}

	$difficulty_value = get_post_meta( get_the_ID(), 'meta-box-dropdown', true );
	$difficulty_value_new = '';
	// Check if the custom field has a value.
	if ( ! empty( $difficulty_value ) ) {
	    $difficulty_value_new = "<div class='diy-item'>Difficulty Level: $difficulty_value</div>";
	}

	$duration_value = get_post_meta( get_the_ID(), 'meta-box-dropdown-duration', true );
	$duration_value_new = '';
	// Check if the custom field has a value.
	if ( ! empty( $duration_value ) ) {
	    $duration_value_new = "<div class='diy-item'>Duration: $duration_value</div>";
	}

    $fullcontent = $before . $cost_value_new . $difficulty_value_new . $duration_value_new . $after . $content;
    
    return $fullcontent;
}

add_filter('the_content', 'mv_before_after');

