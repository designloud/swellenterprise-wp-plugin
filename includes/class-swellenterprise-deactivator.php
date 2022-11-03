<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SWELLEnterprise
 * @subpackage SWELLEnterprise/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    SWELLEnterprise
 * @subpackage SWELLEnterprise/includes
 * @author     Your Name <email@example.com>
 */
if( !class_exists('SWELLEnterprise_Deactivator') ) {
class SWELLEnterprise_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		/**
	     * This only required if custom post type has rewrite!
	     */
	    flush_rewrite_rules();
	    // Delete custom field transients for contact/ client/ lead.
	    delete_transient( "swell_lead_custom_fields" );
	    delete_transient( "swell_contact_custom_fields" );
	    delete_transient( "swell_client_custom_fields" );
	    // Delete task status transients for contact/ client/ lead.
	    delete_transient( "swell_task_statuses" );
	    delete_transient( "swell_lead_statuses" );

	    // Deactivating options and webhooks that are set on plugin activation.
	    $swell_api = new SWELLEnterprise_API();

	    $events_array = ['lead.create','lead.update','lead.delete','lead.destroy','client.create','client.update','client.delete','client.destroy','contact.create','contact.update','contact.delete','contact.destroy','note.create','note.update','note.delete','note.destroy','task.create','task.update','task.destroy','task.delete'];
	    foreach ( $events_array as $event ) {
	    	$option_name = 'swell_'.esc_attr( $event ) .'_webhook';
	    	$webhook_option_id = intval( get_option( $option_name ) );
	    	$swell_Webhook_deleted_data = $swell_api->delete_swell_webhook( $webhook_option_id, $option_name );
	    }
	    
	}
}
}