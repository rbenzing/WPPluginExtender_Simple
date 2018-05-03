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
			// register our scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
			
			// register our admin sidebar menu link and plugin page settings link
			add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
			
			// register our extender_gallery_settings_init to the admin_init action hook
		 	add_action( 'admin_init', array( $this, 'settings_init' ) );
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
			register_post_type( 'extender_gallery', array(
                           'labels'      => array(
                               'name'          => __('Photo Gallery'),
                               'singular_name' => __('Photo Gallery'),
                           ),
                           'public'      => true,
                           'has_archive' => true,
                       ) );
		}
		
		// custom option and settings
		function settings_init() {
			// register a new setting for "extender-photo-gallery" page
			register_setting( 'extender_gallery', 'extender_gallery_options' );
		 
			// register a new section in the "extender-photo-gallery" page
		 	add_settings_section(
				'extender_admin_section', 
				__( 'The Simplest Photo Gallery In The World', 'extender-photo-gallery' ), 
				array($this, 'extender_settings_section_callback'), 
				'extender_gallery'
			);
		
			add_settings_field( 
				'extender_text_field', 
				__( 'Text Field', 'extender-photo-gallery' ), 
				array($this, 'extender_text_field_render'), 
				'extender_gallery', 
				'extender_admin_section' 
			);
		
			add_settings_field( 
				'extender_textarea_field', 
				__( 'Textarea Field', 'extender-photo-gallery' ), 
				array($this, 'extender_textarea_field_render'), 
				'extender_gallery', 
				'extender_admin_section' 
			);
		
			add_settings_field( 
				'extender_select_field', 
				__( 'Select Field', 'extender-photo-gallery' ), 
				array($this, 'extender_select_field_render'), 
				'extender_gallery', 
				'extender_admin_section' 
			);
		
			add_settings_field( 
				'extender_radio_field', 
				__( 'Radio field description', 'extender-photo-gallery' ), 
				array($this, 'extender_radio_field_render'), 
				'extender_gallery', 
				'extender_admin_section'
			);
		
		}
		
		// Text field html callback
		function extender_text_field_render() { 
			$options = get_option( 'extender_gallery_options' );
			?>
			<input type='text' name='extender_settings[extender_text_field]' value='<?php echo esc_attr( $options['extender_text_field'] ); ?>'>
			<?php
		}
		
		// textarea field html callback
		function extender_textarea_field_render() { 
			$options = get_option( 'extender_gallery_options' );
			?>
			<textarea cols='40' rows='5' name='extender_settings[extender_textarea_field_textarea]'> 
				<?php echo esc_textarea( $options['extender_textarea_field_textarea'] ); ?>
		 	</textarea>
			<?php
		}
		
		// select field html callback
		function extender_select_field_render() { 
			$options = get_option( 'extender_gallery_options' );
			?>
			<select name='extender_settings[extender_select_field]'>
				<option value='1' <?php selected( $options['extender_select_field'], 1 ); ?>>Option 1</option>
				<option value='2' <?php selected( $options['extender_select_field'], 2 ); ?>>Option 2</option>
				<option value='3' <?php selected( $options['extender_select_field'], 3 ); ?>>Option 3</option>
				<option value='4' <?php selected( $options['extender_select_field'], 4 ); ?>>Option 4</option>
			</select>
			<?php
		}
		
		// radio field html callback
		function extender_radio_field_render() { 
			$options = get_option( 'extender_gallery_options' );
			?>
			<input type='radio' name='extender_settings[extender_radio_field]' <?php checked( $options['extender_radio_field'], 1 ); ?> value='1'> Yes
			<input type='radio' name='extender_settings[extender_radio_field]' <?php checked( $options['extender_radio_field'], 0 ); ?> value='0'> No
			<?php
		}
		
		function extender_settings_section_callback() { 
			echo __( 'This is the section description', 'extender-photo-gallery' );
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