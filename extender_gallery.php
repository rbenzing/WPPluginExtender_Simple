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
		public $slug;
		private $settings;
		private $options;
		
		function __construct() {
			
			// Register variable for plugin file
			$this->plugin = plugin_basename( __FILE__ );
			
			// Register variable for plugin slug
			$info = pathinfo( __FILE__ );
			$this->slug = basename( __FILE__, '.'.$info['extension'] );
			
			// Register settings & options variables
			$this->settings = $this->slug.'_settings';
			$this->options = $this->slug.'_options';
		}
		
		function register() {
			// register our scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
			
			// register our admin sidebar menu link and plugin page settings link
			add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
			add_filter( "plugin_action_links_$this->slug", array( $this, 'settings_link' ) );
			
			// register our settings_init to the admin_init action hook
		 	add_action( 'admin_init', array( $this, 'settings_init' ) );
		}
		
		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page='.$this->options.'">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}
		
		// Add our admin menu to WordPress admin sidebar with a custom icon and positioned last.
		public function add_admin_page() {
			
			// Add our Main Menu for our Plugin ( we could add a page just replacing '' with "array( $this, 'admin_index_page' )" )
			add_menu_page( __( 'Extender Photo Gallery Admin', 'extender-photo-gallery' ), __( 'Extender Photo Gallery', 'extender-photo-gallery' ), 'manage_options', $this->slug, '', 'dashicons-images-alt', 110 );
			
			// Add our Settings Submenu for our Plugin
			add_submenu_page( $this->slug, __( 'Extender Photo Gallery', 'extender-photo-gallery' ), __( 'Welcome', 'extender-photo-gallery' ), 'manage_options', $this->slug, array( $this, 'admin_index_page' ) );
			
			// Add our Settings Submenu for our Plugin
			add_submenu_page( $this->slug, __( 'Extender Photo Gallery Settings', 'extender-photo-gallery' ), __( 'Settings', 'extender-photo-gallery' ), 'manage_options', $this->options, array( $this, 'admin_options_page' ) );
		}
		
		// Callback for submenu options page
		public function admin_options_page() {
			require_once PLUGIN_PATH . 'templates/admin.php';
		}
		
		// Callback for displaying our admin html page
		public function admin_index_page() {
			require_once PLUGIN_PATH . 'templates/welcome.php';
		}
		
		// Run our register custom post type on WordPress initialization
		protected function create_post_type() {
			add_action( 'init', array( $this, 'custom_post_type_callback' ) );
		}
		
		// Register our gallery post type
		function custom_post_type_callback() {
			register_post_type( $this->slug, array(
                           'labels'      => array(
                               'name'          => __('Photo Gallery'),
                               'singular_name' => __('Photo Gallery'),
                           ),
                           'public'      => true,
                           'has_archive' => true,
                           'rewrite' => array( 'slug' => 'gallery' )
                       ) );
		}
		
		// custom option and settings
		function settings_init() {
			// register a new section in the "extender_gallery" page
		 	add_settings_section(
				$this->slug.'_admin_section', 
				__( 'Settings Section Title', 'extender-photo-gallery' ), 
				array( $this, 'settings_section_callback' ), 
				$this->options
			);
			
			// register a text field
			add_settings_field( 
				$this->slug.'_text_field', 
				__( 'Text Field', 'extender-photo-gallery' ), 
				array( $this, 'text_field_render' ),
				$this->options, 
				$this->slug.'_admin_section' 
			);
			
			// register a textarea field
			add_settings_field( 
				$this->slug.'_textarea_field', 
				__( 'Textarea Field', 'extender-photo-gallery' ), 
				array( $this, 'textarea_field_render' ), 
				$this->options, 
				$this->slug.'_admin_section' 
			);
			
			// register a select field
			add_settings_field( 
				$this->slug.'_select_field', 
				__( 'Select Field', 'extender-photo-gallery' ), 
				array( $this, 'select_field_render' ), 
				$this->options, 
				$this->slug.'_admin_section' 
			);
			
			// register a radio field
			add_settings_field( 
				$this->slug.'_radio_field', 
				__( 'Radio field description', 'extender-photo-gallery' ), 
				array( $this, 'radio_field_render' ), 
				$this->options, 
				$this->slug.'_admin_section'
			);
			
			// register a new setting for "extender_gallery_options" page
			register_setting( $this->settings, $this->options, array( $this, 'sanitize_data' ) );
		 
		}
		
		// Sanitize your data
		function sanitize_data( $input ) {
			
			if ( ! is_array( $input ) ) return;
						
			if( isset( $input[$this->slug.'_text_field'] ) ) {
				$output[$this->slug.'_text_field'] = sanitize_text_field( $input[$this->slug.'_text_field'] );
			}
			
			if( isset( $input[$this->slug.'_textarea_field'] ) ) {
				$output[$this->slug.'_textarea_field'] = sanitize_textarea_field( $input[$this->slug.'_textarea_field'] );
			}
			
			if( isset( $input[$this->slug.'_select_field'] ) ) {
				$output[$this->slug.'_select_field'] = sanitize_text_field( $input[$this->slug.'_select_field'] );
			}
			
			if( isset( $input[$this->slug.'_radio_field'] ) ) {
				// You could also use "intval()" php function to make sure the variable is integer.
				$output[$this->slug.'_radio_field'] = filter_var( $input[$this->slug.'_radio_field'], FILTER_SANITIZE_NUMBER_INT );
			}
			
			return $output;
		} 
		
		// Text field html callback
		function text_field_render() { 
			$options = get_option( $this->slug.'_settings' );
			?>
			<input type='text' name='<?php echo $this->slug; ?>_settings[<?php echo $this->slug; ?>_text_field]' value='<?php echo esc_attr( $options[$this->slug.'_text_field'] ); ?>'>
			<?php
		}
		
		// textarea field html callback
		function textarea_field_render() { 
			$options = get_option( $this->slug.'_settings' );
			?>
			<textarea cols='40' rows='5' name='<?php echo $this->slug; ?>_settings[<?php echo $this->slug; ?>_textarea_field]'> 
				<?php echo esc_textarea( $options[$this->slug.'_textarea_field'] ); ?>
		 	</textarea>
			<?php
		}
		
		// select field html callback
		function select_field_render() { 
			$options = get_option( $this->slug.'_settings' );
			?>
			<select name='<?php echo $this->slug; ?>_settings[<?php echo $this->slug; ?>_select_field]'>
				<option value='option1' <?php selected( $options[$this->slug.'_select_field'], 'option1' ); ?>>Option 1</option>
				<option value='option2' <?php selected( $options[$this->slug.'_select_field'], 'option2' ); ?>>Option 2</option>
				<option value='option3' <?php selected( $options[$this->slug.'_select_field'], 'option3' ); ?>>Option 3</option>
				<option value='option4' <?php selected( $options[$this->slug.'_select_field'], 'option4' ); ?>>Option 4</option>
			</select>
			<?php
		}
		
		// radio field html callback
		function radio_field_render() { 
			$options = get_option( $this->slug.'_settings' );
			?>
			<input type='radio' name='<?php echo $this->slug; ?>_settings[<?php echo $this->slug; ?>_radio_field]' <?php checked( $options[$this->slug.'_radio_field'], 1 ); ?> value='1'> Yes
			<input type='radio' name='<?php echo $this->slug; ?>_settings[<?php echo $this->slug; ?>_radio_field]' <?php checked( $options[$this->slug.'_radio_field'], 0 ); ?> value='0'> No
			<?php
		}
		
		function settings_section_callback() { 
			echo __( 'This is the section description', 'extender-photo-gallery' );
		}
		
		// Enqueue js and css files
		function enqueue() {
			// Include Litty Library
			wp_enqueue_style( $this->slug.'_lity_style', plugins_url( '/assets/lity.min.css', __FILE__ ) );
			wp_enqueue_script( $this->slug.'_lity_script', plugins_url( '/assets/lity.min.js', __FILE__ ) );
			
			// Only enqueue for admin users
			if (is_admin()) {
				wp_enqueue_style( $this->slug.'_style', plugins_url( '/assets/style.css', __FILE__ ) );
				wp_enqueue_script( $this->slug.'_script', plugins_url( '/assets/script.js', __FILE__ ) );
			}
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