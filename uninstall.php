<?php
/**
 * @package WPPluginExtender
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}
// Clear Database stored data
$gallery = get_posts( array( 'post_type' => 'extender_gallery', 'numberposts' => -1 ) );
foreach( $gallery as $photo ) {
	wp_delete_post( $photo->ID, true );
}
// Access the database via SQL
global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'extender_gallery'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );