<?php
/**
 * @since             1.0.0
 * @package           WPPluginExtender
 *
 * Plugin Name:       Extender Photo Gallery
 * Plugin URI:        http://github.com/rbenzing/WPPluginExtenderPhotoGallery
 * Description:       This OOP plugin is used in a workshop on WordPress plugin development.
 * Version:           1.0.0
 * Author:            Russell Benzing
 * Author URI:        russellbenzing.com
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       extender-photo-gallery
 * Domain Path:       /languages
 */
 
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/
 
defined( 'WPINC' ) or die('Hack Attempt'); // Can also use ABSPATH

define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*
Using Composer (Dependency Manager) you can autoload your plugin classes to make
development easier. You would need to remove all requires/includes and change your
file structure to use a namespace PSR-4 structure. Learn more by visiting
http://getcomposer.org

if( file_exists( PLUGIN_PATH . '/vendor/autoload.php' ) ) {
	require_once PLUGIN_PATH . '/vendor/autoload.php';
}
 
*/

if(!class_exists('WPPluginExtenderPhotoGallery')) {

	class WPPluginExtenderPhotoGallery {
		
		public $plugin;
		
		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}
		
		function register() {
			// register our extender_gallery_settings_init to the admin_init action hook
		 	add_action( 'admin_init', 'extender_gallery_settings_init' );
		 	
		 	// register our scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
			
			// register our admin sidebar menu link and plugin page settings link
			add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		}
		
		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=extender_gallery">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}
		
		// Add our admin menu to WordPress admin sidebar with a custom icon and positioned last.
		public function add_admin_page() {
			add_menu_page( 'Extender Photo Gallery Admin', 'Extender Photo Gallery', 'manage_options', 'extender_gallery', array( $this, 'admin_index_page' ), 'dashicons-images-alt', 110 );
		}
		
		// Callback for displaying our admin html page
		public function admin_index_page() {
			require_once PLUGIN_PATH . 'templates/admin.php';
		}
		
		// Run our register custom post type on WordPress initialization
		protected function create_post_type() {
			add_action( 'init', array( $this, 'custom_post_type_callback' ) );
		}
		
		// Register our gallery post type
		function custom_post_type_callback() {
			register_post_type( 'extender_gallery', ['public' => true, 'label' => 'Extender Photo Gallery'] );
		}
		
		// custom option and settings
		function extender_gallery_settings_init() {
			// register a new setting for "extender-photo-gallery" page
			register_setting( 'extender_gallery', 'extender_gallery_options' );
		 
			// register a new section in the "extender-photo-gallery" page
		 	add_settings_section( 'extender_gallery_section_developers', __( 'The Matrix has you.', 'extender-photo-gallery' ), 'extender_gallery_section_developers_cb', 'extender_gallery' );
		 
		 	// register a new field in the "extender_gallery_section_developers" section, inside the "extender-photo-gallery" page
		 	add_settings_field( 'extender_gallery_field_pill', // as of WP 4.6 this value is used only internally
		 	// use $args' label_for to populate the id inside the callback
		 	__( 'Pill', 'extender-photo-gallery' ), 'extender_gallery_field_pill_cb', 'extender_gallery', 'extender_gallery_section_developers', [ 'label_for' => 'extender_gallery_field_pill', 'class' => 'extender_gallery_row', 'extender_gallery_custom_data' => 'custom' ] );
		}
		
		/*
		* section callbacks can accept an $args parameter, which is an array.
		* $args have the following keys defined: title, id, callback.
		* the values are defined at the add_settings_section() function.
		*/
		function extender_gallery_section_developers_cb( $args ) {
		?>
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'extender-photo-gallery' ); ?></p>
		<?php
		}
		 
		/* 
		* field callbacks can accept an $args parameter, which is an array.
		* $args is defined at the add_settings_field() function.
		* wordpress has magic interaction with the following keys: label_for, class.
		* the "label_for" key value is used for the "for" attribute of the <label>.
		* the "class" key value is used for the "class" attribute of the <tr> containing the field.
		* you can add custom key value pairs to be used inside your callbacks.
		*/
		function extender_gallery_field_pill_cb( $args ) {
			// get the value of the setting we've registered with register_setting()
			$options = get_option( 'extender_gallery_options' );
			// output the field
			?>
			<select id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args['extender_gallery_custom_data'] ); ?>" name="extender_gallery_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		 		<option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
		 			<?php esc_html_e( 'red pill', 'extender-photo-gallery' ); ?>
		 		</option>
		 		<option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
		 			<?php esc_html_e( 'blue pill', 'extender-photo-gallery' ); ?>
		 		</option>
		 	</select>
		 	<p class="description">
		 		<?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'extender-photo-gallery' ); ?>
			</p>
		 	<p class="description">
		 		<?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'extender-photo-gallery' ); ?>
			</p>
		<?php
		}
		
		// Enqueue js and css files
		function enqueue() {
			// Include Litty Library
			wp_enqueue_style( 'extender_gallery_lity_style', plugins_url( '/assets/lity.min.css', __FILE__ ) );
			wp_enqueue_script( 'extender_gallery_lity_script', plugins_url( '/assets/lity.min.js', __FILE__ ) );
			
			wp_enqueue_style( 'extender_gallery_style', plugins_url( '/assets/style.css', __FILE__ ) );
			wp_enqueue_script( 'extender_gallery_script', plugins_url( '/assets/script.js', __FILE__ ) );
		}
		
		// Activation run script
		function activate() {
			// We could just write 'flush_rewrite_rules()' or we can include custom classes for more OOP approach.
			require_once PLUGIN_PATH . 'inc/activate.php';
			WPPluginExtenderActivate::activate();
		}
		
		// Deactivation run script
		function deactivate() {
			flush_rewrite_rules();
		}
	}
	
}
$extender_gallery = new WPPluginExtenderPhotoGallery();
$extender_gallery->register();

// WordPress activation hook
register_activation_hook( __FILE__, array( $extender_gallery, 'activate' ) );

// WordPress deactivation hook
register_deactivation_hook( __FILE__, array( $extender_gallery, 'deactivate' ) );