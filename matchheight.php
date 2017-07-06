<?php     namespace ng_matchheight;

/*
Plugin Name: matchHeight
Plugin URI: http://wpbeaches.com/
Description: Adds the matchHeight jQuery plugin which makes the height of all selected elements exactly equal
Author: Neil Gee
Version: 1.2.0
Author URI: http://wpbeaches.com
Text Domain: matchheight
Domain Path: /languages/
@package    matchheight
@author     Neil Gee
@since      1.0.0
@license    GPL-2.0+
*/


// If called direct, refuse
  if ( ! defined( 'ABSPATH' ) ) {
          die;
  }

/* Assign global variables */

$plugin_url = WP_PLUGIN_URL . '/matchheight';
$options = array();

/**
 * Register our text domain.
 *
 * @since 1.0.0
 */

function load_textdomain() {
  load_plugin_textdomain( 'matchheight', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_textdomain' );

/**
 * Register and Enqueue Scripts and Styles
 *
 * @since 1.0.0
 */

//Script-tac-ulous -> All the Scripts and Styles Registered and Enqueued
function scripts_styles() {

  $options = get_option( 'matchheight_settings' );

  if( isset($options['mh_selectors'] )) {

	wp_register_script( 'matchheight', plugins_url( '/js/jquery.matchHeight-min.js', __FILE__ ), array( 'jquery' ), '0.7.0', true );
	wp_register_script( 'matchheight-init', plugins_url( '/js/matchHeight-init.js',  __FILE__ ), array( 'matchheight' ), '1.0.0', true );

	wp_enqueue_script( 'matchheight' );

     $data = array (

      'mh_inner_array' => array(

          'mh_selectors'  => $options['mh_selectors'], // this the selectors field

      ),
  );

    // Pass PHP variables to jQuery script
    wp_localize_script( 'matchheight-init', 'matchVars', $data );

    wp_enqueue_script( 'matchheight-init' );
  }



}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts_styles' );

/**
 * Register our option fields
 *
 * @since 1.0.0
 */

function plugin_settings(){
  register_Setting(
        'mh_settings-group', //option name
        'matchheight_settings',// option group setting name and option name
        __NAMESPACE__ . '\\matchheight_validate_input' //sanitize the inputs
  );

  add_settings_section(
        'mh_matchheight_section', //declare the section id
        'matchHeight Settings', //page title
         __NAMESPACE__ . '\\mh_matchheight_section_callback', //callback function below
        'matchheight' //page that it appears on

    );
  add_settings_field(
        'mh_selectors', //unique id of field
        'Add Element Selectors', //title
         __NAMESPACE__ . '\\mh_selectors_callback', //callback function below
        'matchheight', //page that it appears on
        'mh_matchheight_section' //settings section declared in add_settings_section
    );
}
add_action('admin_init', __NAMESPACE__ . '\\plugin_settings');



/**
 * Sanitize our inputs
 *
 * @since 1.0.0
 */

function matchheight_validate_input( $input ) {
   // Create our array for storing the validated options
    $output = array();

    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {

        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {

            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );

        } // end if


    } // end foreach

    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'matchheight_validate_input' , $output, $input );
}

function mh_matchheight_section_callback() {

}

/**
 * Register Our Input to select elements fot equal height
 *
 * @since 1.0.0
 */

function mh_selectors_callback() {
$options = get_option( 'matchheight_settings' );

if( !isset( $options['mh_selectors'] ) ) $options['mh_selectors'] = '';
echo '<input type="text" id="mh_selectors" name="matchheight_settings[mh_selectors]" value="' . sanitize_text_field($options['mh_selectors']) . '" placeholder="Add element CSS Class or ID to equal in height" class="large-text" />';
echo '<span class="description">' . esc_attr_e ( 'Add elements CSS Class or ID to be equal in height, comma separate multiple elements','matchheight') . '</span>';
}


/**
 * Create the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_page() {

    /*
     * Use the add options_page function
     * add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function )
     */

     add_options_page(
        __( 'matchHeight Options Plugin','matchheight' ), //$page_title
        __( 'matchHeight', 'matchheight' ), //$menu_title
        'manage_options', //$capability
        'matchheight', //$menu-slug
        __NAMESPACE__ . '\\plugin_options_page' //$function
      );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\plugin_page' );

/**
 * Include the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_options_page() {

    if( !current_user_can( 'manage_options' ) ) {

      wp_die( "Hall and Oates 'Say No Go'" );
    }

   require( 'inc/options-page-wrapper.php' );
}
