<?php
// check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }
 
 // add error/update messages
 
 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_GET['settings-updated'] ) ) {
 // add settings saved message with the class of "updated"
 add_settings_error( 'extender_gallery_messages', 'extender_gallery_message', __( 'Settings Saved', 'extender_gallery' ), 'updated' );
 }
 
 // show error/update messages
 settings_errors( 'extender_gallery_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "wporg"
 settings_fields( 'extender_gallery_options' );
 // output setting sections and their fields
 // (sections are registered for "extender-photo-gallery", each field is registered to a specific section)
 do_settings_sections( 'extender_gallery_options' );
 // output save settings button
 submit_button( 'Save Settings' );
 ?>
 </form>
 </div>