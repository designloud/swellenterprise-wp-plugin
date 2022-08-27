<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SWELLEnterprise
 * @subpackage SWELLEnterprise/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SWELLEnterprise
 * @subpackage SWELLEnterprise/admin
 * @author     Your Name <email@example.com>
 */
class SWELLEnterprise_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $swellenterprise    The ID of this plugin.
	 */
	private $swellenterprise;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $error;
	private $message;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $swellenterprise       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $swellenterprise, $version, $error = NULL, $message = NULL ) {

		$this->plugin_name = $swellenterprise;
		$this->version = $version;
		$this->error = $error;
		$this->message = $message;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in SWELLEnterprise_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The SWELLEnterprise_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->swellenterprise, plugin_dir_url( __FILE__ ) . 'css/swellenterprise-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in SWELLEnterprise_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The SWELLEnterprise_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->swellenterprise, plugin_dir_url( __FILE__ ) . 'js/swellenterprise-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
     * This function runs when WordPress completes its upgrade process
     * It iterates through each plugin updated to see if ours is included
     *
     * @param $upgrader_object Array
     * @param $options Array
     * @link https://catapultthemes.com/wordpress-plugin-update-hook-upgrader_process_complete/
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/upgrader_process_complete
     */
    public function upgrader_process_complete( $upgrader_object, $options ) {

        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {

            // Iterate through the plugins being updated and check if ours is there
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == SWELLENTERPRISE_BASE_NAME ) {

					// Your code here, eg display a message:

                    // Set a transient to record that our plugin has just been updated
					set_transient( $this->plugin_name . '_updated', 1 );
					set_transient( $this->plugin_name . '_updated_message', esc_html__( 'Thanks for updating', 'exopite_sof' ) );

                }
            }
        }
    }

    /**
     * Show a notice to anyone who has just updated this plugin
     * This notice shouldn't display to anyone who has just installed the plugin for the first time
     */
    public function display_update_notice() {

        // Check the transient to see if we've just activated the plugin
        if( get_transient( $this->plugin_name . '_updated' ) ) {

			/**
			 * Display a message.
			 */
            // @link https://digwp.com/2016/05/wordpress-admin-notices/
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . get_transient( 'exopite_sof_updated_message' ) . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

            // Delete the transient so we don't keep displaying the activation message
            delete_transient( $this->plugin_name . '_updated' );
            delete_transient( $this->plugin_name . '_updated_message' );
		}

    }

	public function create_menu() {

	    /*
	    * Create a submenu page under Plugins.
	    * Framework also add "Settings" to your plugin in plugins list.
	    */
	    $config_submenu = array(

	        'type'              => 'menu',                          // Required, menu or metabox
	        'id'                => $this->plugin_name . '-test',    // Required, meta box id, unique per page, to save: get_option( id )
	        'parent'            => 'plugins.php',                   // Required, sub page to your options page
	        // 'parent'            => 'edit.php?post_type=your_post_type',
	        'submenu'           => true,                            // Required for submenu
	        'title'             => esc_html__( 'SWELLEnterprise', 'plugin-name' ),    //The name of this page
	        'capability'        => 'manage_options',                // The capability needed to view the page
	        'plugin_basename'   => plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
	        // 'tabbed'            => false,

	    );

	    //
	    // - OR --
	    // eg: if Yoast SEO is active, then add submenu under Yoast SEO admin menu,
	    // if not then under Plugins admin menu:
	    //

	    if ( ! function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

	    $parent = ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) ? 'wpseo_dashboard' : 'plugins.php';
	    $settings_link = ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) ? 'admin.php?page=plugin-name' : 'plugins.php?page=plugin-name';

	    $config_submenu = array(

	        'type'              => 'menu',                                          // Required, menu or metabox
	        'id'                => $this->plugin_name,                              // Required, meta box id, unique per page, to save: get_option( id )
	        'menu'              => $parent,                                         // Required, sub page to your options page
	        'submenu'           => true,                                            // Required for submenu
	        'settings-link'     => $settings_link,
	        'title'             => esc_html__( 'SWELLEnterprise', 'plugin-name' ),    //The name of this page
	        'capability'        => 'manage_options',                                // The capability needed to view the page
	        'plugin_basename'   => plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
	        'tabbed'            => true,

	    );

	    $fields[] = array(
            'name'   => 'welcome',
            'title'  => 'Welcome',
            'icon'   => 'fa fa-align-justify',
            'fields' => array(

            	array(
                    'type'    => 'card',
                    'class'   => 'text-class', // for all fields
                    'title'   => 'Welcome to SWELLEnterprise',
                    'content' => '',
                    'header' => 'Header Text',
                    'footer' => 'Footer Text',
                ),
                array(
                    'id'          => 'swell_welcome',
                    'type'        => 'content',
                    'title'       => 'Welcome to SWELLEnterprise',
                    'class'       => 'text-class',
                    'description' => '<div class="row custom-info">
                                 <div class="col-6 swell-video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/ELQrbMUGnRA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                 </div>
                                  <div class="col-12 swell-about">
                                    
                                    <p>SWELLEnterprise makes it easier and less expensive for small business owners to manage projects, clients, business development and communication with remote team members. It’s particularly loved by creative service agencies because that’s who designed it. Our cloud-based system manages everything from invoicing to documents, projects to email marketing, replacing 3 or 4 products with just one. </p>
                                    <p>
                                       <strong>While you do need an account with SWELLEnterprise to make full use of this plugin, the plugin should continue to work and function without an account.</strong>
                                    </p>
                                    <p>
                                       <a class="btn btn-primary" target="_blank" href="https://app.swellsystem.com/register?utm_source=wordpress&utm_medium=plugin&utm_campaign=intro_page">Try FREE For 30 Days »</a>
                                    </p>
                                 </div>
                              </div>',
                    // 'default'     => 'john@doe.com',
                    'attributes'    => array(
                        'rows'        => 10,
                        'cols'        => 5,
                        // 'placeholder' => 'you@example.com',
                    ),
                ),
                // array(
                //     'type'    => 'content',
                //     'class'   => 'text-class', // for all fields
                //     'content' => '<div class="row">
                //                  <div class="col-6">
                //                     <iframe width="560" height="315" src="https://www.youtube.com/embed/ELQrbMUGnRA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                //                  </div>
                //                   <div class="col-6">
                //                     <h2></h2>
                //                     <p> </p>
                //                     <p>
                //                        <strong>While you do need an account with SWELLEnterprise to make full use of this plugin, the plugin should continue to work and function without an account.</strong>
                //                     </p>
                //                     <p>
                //                        <a class="btn btn-primary" target="_blank" href="https://app.swellsystem.com/register?utm_source=wordpress&utm_medium=plugin&utm_campaign=intro_page">Try FREE For 30 Days »</a>
                //                     </p>
                //                  </div>
                //               </div>',

                // ),

            )
        );

        $fields[] = array(
            'name'   => 'authentication',
            'title'  => 'Authentication',
            'icon'   => 'dashicons-portfolio',
            'fields' => array(

                array(
                    'id'          => 'username',
                    'type'        => 'text',
                    'title'       => 'Username',
                    'class'       => 'text-class',
                    'description' => 'Use your email associated with your SWELLEnterprise account.',
                    'default'     => 'john@doe.com',
                    'attributes'    => array(
                        'rows'        => 10,
                        'cols'        => 5,
                        'placeholder' => 'you@example.com',
                    ),
                ),

                array(
                    'id'     => 'password',
                    'class'  => 'pswrd',
                    'type'   => 'text',
                    'title'  => 'Password',
                ),

            )
        );

        $fields[] = array(
	        'name'   => 'configuration',
	        'title'  => 'Configuration',
	        'icon'   => 'dashicons-admin-generic',
	        'fields' => array(

	        	array(
                    'id'      => 'client_switcher',
                    'type'    => 'switcher',
                    'title'   => 'Clients',
                    'label'   => 'Do you want to create and sync your clients?',
                    'default' => 'yes',
                ),
                array(
                    'id'      => 'lead_switcher',
                    'type'    => 'switcher',
                    'title'   => 'Leads',
                    'label'   => 'Do you want to create and sync your leads?',
                    'default' => 'yes',
                ),
	        	array(
                    'id'      => 'contact_switcher',
                    'type'    => 'switcher',
                    'title'   => 'Contacts',
                    'label'   => 'Do you want to create and sync your contacts?',
                    'default' => 'yes',
                ),
	          // array(
                //     'id'      => 'form_switcher',
                //     'type'    => 'switcher',
                //     'title'   => 'Forms',
                //     'label'   => 'Do you enable the form builder and sync your forms?',
                //     'default' => 'yes',
                // ),
                array(
                    'id'      => 'note_switcher',
                    'type'    => 'switcher',
                    'title'   => 'Notes',
                    'label'   => 'Do you want to create and sync your notes?',
                    'default' => 'yes',
			    /**
			     * Second Tab
			     */

                ),
                array(
                    'id'      => 'task_switcher',
                    'type'    => 'switcher',
                    'title'   => 'Tasks',
                    'label'   => 'Do you want to create and sync your tasks?',
                    'default' => 'yes',
                ),
//                array(
//                    'id'      => 'history_switcher',
//                    'type'    => 'switcher',
//                    'title'   => 'Hi',
//                    'label'   => 'Do you enable the form builder and sync your forms?',
//                    'default' => 'yes',
//                ),
            ),

        );

//         $fields[] = array(
// 	        'name'   => 'webhooks',
// 	        'title'  => 'Webhooks',
// 	        'icon'   => 'dashicons-admin-generic',
// 	        'fields' => array(
//                 array(
//                     'id'      => 'lead_create_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Leads Create Webhook',
//                     'default' => get_option('swell_lead.create_webhook'),
//                 ),
//                 array(
//                     'id'      => 'lead_update_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Leads Update Webhook',
//                     'default' => get_option('swell_lead.update_webhook'),
//                 ),
//                 array(
//                     'id'      => 'lead_delete_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Leads Delete Webhook',
//                     'default' => get_option('swell_lead.delete_webhook'),
//                 ),
//                 array(
//                     'id'      => 'lead_destroy_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Leads Destroy Webhook',
//                     'default' => get_option('swell_lead.destroy_webhook'),
//                 ),
// 	        	array(
//                     'id'      => 'client_create_webhook',
//                     'name'    => 'client_create_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Clients Create Webhook',
//                     'default' => get_option('swell_client.create_webhook'),
//                 ),
//                 array(
//                     'id'      => 'client_update_webhook',
//                     'name'    => 'client_update_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Clients Update Webhook',
//                     'default' => get_option('swell_client.update_webhook'),
//                 ),
//                 array(
//                     'id'      => 'client_delete_webhook',
//                     'name'    => 'client_delete_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Clients Delete Webhook',
//                     'default' => get_option('swell_client.delete_webhook'),
//                 ),
//                 array(
//                     'id'      => 'client_destroy_webhook',
//                     'name'    => 'client_destroy_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Clients Destroy Webhook',
//                     'default' => get_option('swell_client.destroy_webhook'),
//                 ),
// 	        	array(
//                     'id'      => 'contact_create_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Contacts Create Webhook',
//                     'default' => get_option('swell_contact.create_webhook'),
//                 ),
//                 array(
//                     'id'      => 'contact_update_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Contacts Update Webhook',
//                     'default' => get_option('swell_contact.update_webhook'),
//                 ),
//                 array(
//                     'id'      => 'contact_delete_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Contacts Delete Webhook',
//                     'default' => get_option('swell_contact.delete_webhook'),
//                 ),
//                 array(
//                     'id'      => 'contact_destroy_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Contacts Destroy Webhook',
//                     'default' => get_option('swell_contact.destroy_webhook'),
//                 ),
//                 array(
//                     'id'      => 'note_create_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Notes Create Webhook',
//                     'default' => get_option('swell_note.create_webhook'),
//                 ),
//                 array(
//                     'id'      => 'note_update_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Notes Update Webhook',
//                     'default' => get_option('swell_note.update_webhook'),
//                 ),
//                 array(
//                     'id'      => 'note_delete_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Notes Delete Webhook',
//                     'default' => get_option('swell_note.delete_webhook'),
//                 ),
//                 array(
//                     'id'      => 'note_destroy_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Notes Destroy Webhook',
//                     'default' => get_option('swell_note.destroy_webhook'),
//                 ),
//                 array(
//                     'id'      => 'task_create_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Tasks Create Webhook',
//                     'default' => get_option('swell_task.create_webhook'),
//                 ),
//                 array(
//                     'id'      => 'task_update_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Tasks Update Webhook',
//                     'default' => get_option('swell_task.update_webhook'),
//                 ),
//                 array(
//                     'id'      => 'task_destroy_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Tasks Destroy Webhook',
//                     'default' => get_option('swell_task.destroy_webhook'),
//                 ),
//                 array(
//                     'id'      => 'task_delete_webhook',
//                     'type'    => 'text',
//                     'title'   => 'Tasks Delete Webhook',
//                     'default' => get_option('swell_task.delete_webhook'),
//                 ),
                
                
// //                array(
// //                    'id'      => 'history_create_webhook',
// //                    'type'    => 'switcher',
// //                    'title'   => 'Hi',
// //                    'label'   => 'Do you enable the form builder and sync your forms?',
// //                    'default' => 'yes',
// //                ),
//             ),
//         );

	    /**
	     * Third Tab
	     */
//	    $fields[] = array(
//            'name'   => 'basic',
//            'title'  => 'Basic',
//            'icon'   => 'dashicons-admin-generic',
//            'fields' => array(
//
//
//                array(
//                    'id'          => 'text_1',
//                    'type'        => 'text',
//                    'title'       => 'Text',
//                    'before'      => 'Text Before',
//                    'after'       => 'Text After',
//                    'class'       => 'text-class',
//                    'description' => 'Description',
//                    'default'     => 'Default Text',
//                    'attributes'    => array(
//                       'rows'        => 10,
//                       'cols'        => 5,
//                       'placeholder' => 'do stuff',
//                       'data-test'   => 'test',
//
//                    ),
//                    'help'        => 'Help text',
//
//                ),
//
//                array(
//                    'id'     => 'test_disabled',
//                    'type'   => 'text',
//                    'title'  => 'Disabled',
//                    'attributes'     => array(
//                    'placeholder' => 'This is a diabled element',
//                    'disabled'    => 'disabled',
//
//                    ),
//                ),
//
//                array(
//                    'id'      => 'text_icon',
//                    'type'    => 'text',
//                    'title'   => 'Text',
//                    'prepend' => 'fa-font',
//                    'append'  => 'Char',
//                ),
//
//                array(
//                    'id'     => 'password_1',
//                    'type'   => 'password',
//                    'title'  => 'Password',
//                ),
//
//
//
//                array(
//                    'type'    => 'content',
//                    'wrap_class'   => 'no-border-bottom', // for all fieds
//                    'title'   => 'Content Title',
//                    'content' => 'You can "group" element together, by adding the class <code>no-border-bottom</code> to the <code>wrap_class</code>.',
//                    'before' => 'Before Text',
//                    'after'  => 'After Text',
//                ),
//
//                array(
//                    'id'    => 'image_1',
//                    'type'  => 'image',
//                    'title' => 'Image',
//                ),
//
//
//                array(
//                    'id'      => 'switcher_1',
//                    'type'    => 'switcher',
//                    'title'   => 'Switcher',
//                    'label'   => 'You want to do this?',
//                    'default' => 'yes',
//                ),
//
//
//                array(
//                    'id'      => 'hidden_1',
//                    'type'    => 'hidden',
//                    'default' => 'hidden',
//                ),
//
//                array(
//                    'id'    => 'checkbox_1',
//                    'type'  => 'checkbox',
//                    'title' => 'Checkbox',
//                    'label' => 'Did you like this framework ?',
//                    'after' => '<i>If you check this and the other checkbox, a text field will appier.</i>'
//                ),
//
//                array(
//                    'id'    => 'checkbox_2',
//                    'type'  => 'checkbox',
//                    'title' => 'Checkbox Fancy',
//                    'label' => 'Do you want to do this?',
//                    'style'    => 'fancy',
//                ),
//
//                array(
//                    'id'     => 'text_2',
//                    'type'   => 'text',
//                    'title'  => 'Text Test Dependency',
//                    'dependency' => array( 'checkbox_1|checkbox_2', '==|==', 'true|true' ),
//                    'attributes'    => array(
//                        'placeholder' => 'Dependency test',
//                    ),
//                ),
//
//                array(
//                  'id'      => 'radio_1',
//                  'type'    => 'radio',
//                  'title'   => 'Radio',
//                  'options' => array(
//                    'yes'   => 'Yes, Please.',
//                    'no'    => 'No, Thank you.',
//                  ),
//                  'default' => 'no',
//                ),
//
//                array(
//                  'id'      => 'radio_2',
//                  'type'    => 'radio',
//                  'title'   => 'Radio Fancy',
//                  'options' => array(
//                    'yes'   => 'Yes, Please.',
//                    'no'    => 'No, Thank you.',
//                  ),
//                  'default' => 'no',
//                  'style'    => 'fancy',
//                ),
//
//                array(
//                    'id'      => 'test_unknown_1',
//                    'type'    => 'test_unknown_type',
//                    'title'   => 'Test Unknown Element',
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'accordions',
//            'title'  => 'Accordion',
//            'icon'   => 'fa fa-bars',
//            'fields' => array(
//
//                array(
//                    'id'          => 'accordion_1',
//                    'type'        => 'accordion',
//                    'title'       => esc_html__( 'Accordion', 'plugin-name' ),
//                    'options' => array(
//                        'allow_all_open' => false,
//                    ),
//                    'sections'        => array(
//
//                        array(
//                            'options' => array(
//                                'icon'   => 'fa fa-star',
//                                'title'  => 'Section 1',
//                                'closed' => false,
//                            ),
//                            'fields' => array(
//
//                                array(
//                                    'id'    => 'accordion_1_section_1_text_1',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Accordion 1 Text 1', 'plugin-name' ),
//                                    'default' => 'default text',
//                                ),
//
//
//                            ),
//                        ),
//
//                        array(
//                            'options' => array(
//                                'icon'   => 'fa fa-star',
//                                'title'  => 'Section 2',
//                            ),
//                            'fields' => array(
//
//                                array(
//                                    'id'    => 'accordion_1_section_2_text_1',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Accordion 2 Text 1', 'plugin-name' ),
//                                ),
//
//                                array(
//                                    'id'    => 'accordion_1_section_2_text_2',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Accordion 2 Text 2', 'plugin-name' ),
//                                ),
//
//                            ),
//
//                        ),
//
//
//                        array(
//                            'options' => array(
//                                'icon'   => 'fa fa-star',
//                                'title'  => 'Section 3',
//                            ),
//                            'fields' => array(
//
//                                array(
//                                    'id'    => 'accordion_1_section_3_text_1',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Accordion 3 Text 1', 'plugin-name' ),
//                                ),
//
//                                array(
//                                    'id'    => 'accordion_1_section_3_text_2',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Accordion 3 Text 2', 'plugin-name' ),
//                                ),
//
//                            ),
//
//                        ),
//
//                    ),
//
//                ),
//
//
//            ),
//        );
//
//        $fields[] = array(
//            'name'   => 'attached',
//            'title'  => 'Attached',
//            'icon'   => 'dashicons-images-alt',
//            'fields' => array(
//
//                array(
//                    'type'    => 'notice',
//                    'class'   => 'warning',
//                    'content' => 'Metabox only in metabox available.',
//                ),
//
//                array(
//                    'id'      => 'attached_1',
//                    'type'    => 'attached',
//                    'title'   => 'Attached',
//                    'options' => array(
//                        'type' => '', // attach to post (only in metabox)
//                    ),
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'backup',
//            'title'  => 'Backup',
//            'icon'   => 'dashicons-backup',
//            'fields' => array(
//
//                array(
//                    'type'    => 'backup',
//                    'title'   => esc_html__( 'Backup', 'exopite-seo-core' ),
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'buttons',
//            'title'  => 'Button',
//            'icon'   => 'fa fa-toggle-on',
//            'fields' => array(
//
//                array(
//                'id'      => 'button_1',
//                'type'    => 'button',
//                'title'   => 'Button',
//                'options' => array(
//                    'href'      => '#',
//                    'target'    => '_self',
//                    'value'     => 'button',
//                    'btn-class' => 'exopite-sof-btn',
//                ),
//                ),
//
//                array(
//                'id'      => 'button_bar_1',
//                'type'    => 'button_bar',
//                'title'   => 'Button bar',
//                'options' => array(
//                    'one'   => 'One',
//                    'two'   => 'Two',
//                    'three' => 'Three',
//                ),
//                'default' => 'two',
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'colors',
//            'title'  => 'Color',
//            'icon'   => 'dashicons-art',
//            'fields' => array(
//
//                array(
//                    'id'     => 'color_1',
//                    'type'   => 'color',
//                    'title'  => 'Color',
//                ),
//
//                array(
//                    'id'     => 'color_2',
//                    'type'   => 'color',
//                    'title'  => 'Color',
//                    'rgba'   => true,
//                ),
//
//                array(
//                    'id'     => 'color_3',
//                    'type'   => 'color',
//                    'title'  => 'Color',
//                    'picker' => 'html5',
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'contents',
//            'title'  => 'Contents',
//            'icon'   => 'fa fa-align-justify',
//            'fields' => array(
//
//                array(
//                    'type'    => 'card',
//                    'class'   => 'class-name', // for all fieds
//                    'title'   => 'Panel Title',
//                    'content' => '<p>Etiam consectetur commodo ullamcorper. Donec quis diam nulla. Maecenas at mi molestie ex aliquet dignissim a in tortor. Sed in nisl ac mi rutrum feugiat ac sed sem. Nullam tristique ex a tempus volutpat. Sed ut placerat nibh, a iaculis risus. Aliquam sit amet erat vel nunc feugiat viverra. Mauris aliquam arcu in dolor volutpat, sed tempor tellus dignissim.</p><p>Quisque nec lectus vitae velit commodo condimentum ut nec mi. Cras ut ultricies dui. Nam pretium <a href="#">rutrum eros</a> ac facilisis. Morbi vulputate vitae risus ac varius. Quisque sed accumsan diam. Sed elementum eros lectus, et egestas ante hendrerit eu. Proin porta, enim nec dignissim commodo, odio orci maximus tortor, iaculis congue felis velit sed lorem. </p>',
//                    'header' => 'Header Text',
//                    'footer' => 'Footer Text',
//                ),
//
//                array(
//                    'type'    => 'card',
//                    'class'   => 'class-name', // for all fieds
//                    'content' => '<p>Etiam consectetur commodo ullamcorper. Donec quis diam nulla. Maecenas at mi molestie ex aliquet dignissim a in tortor. Sed in nisl ac mi rutrum feugiat ac sed sem. Nullam tristique ex a tempus volutpat. Sed ut placerat nibh, a iaculis risus. Aliquam sit amet erat vel nunc feugiat viverra. Mauris aliquam arcu in dolor volutpat, sed tempor tellus dignissim.</p><p>Quisque nec lectus vitae velit commodo condimentum ut nec mi. Cras ut ultricies dui. Nam pretium <a href="#">rutrum eros</a> ac facilisis. Morbi vulputate vitae risus ac varius. Quisque sed accumsan diam. Sed elementum eros lectus, et egestas ante hendrerit eu. Proin porta, enim nec dignissim commodo, odio orci maximus tortor, iaculis congue felis velit sed lorem. </p>',
//                ),
//
//                array(
//                    'type'    => 'content',
//                    'class'   => 'class-name', // for all fieds
//                    'content' => '<p>Etiam consectetur commodo ullamcorper. Donec quis diam nulla. Maecenas at mi molestie ex aliquet dignissim a in tortor. Sed in nisl ac mi rutrum feugiat ac sed sem. Nullam tristique ex a tempus volutpat. Sed ut placerat nibh, a iaculis risus. Aliquam sit amet erat vel nunc feugiat viverra. Mauris aliquam arcu in dolor volutpat, sed tempor tellus dignissim.</p><p>Quisque nec lectus vitae velit commodo condimentum ut nec mi. Cras ut ultricies dui. </p>',
//
//                ),
//
//                array(
//                    'type'    => 'content',
//                    'class'   => 'class-name', // for all fieds
//                    'title'   => 'Content Title',
//                    'content' => '<p>Etiam consectetur commodo ullamcorper. Donec quis diam nulla. Maecenas at mi molestie ex aliquet dignissim a in tortor. Sed in nisl ac mi rutrum feugiat ac sed sem. Nullam tristique ex a tempus volutpat. Sed ut placerat nibh, a iaculis risus. Aliquam sit amet erat vel nunc feugiat viverra. Mauris aliquam arcu in dolor volutpat, sed tempor tellus dignissim.</p><p>Quisque nec lectus vitae velit commodo condimentum ut nec mi. Cras ut ultricies dui. Nam pretium <a href="#">rutrum eros</a> ac facilisis. Morbi vulputate vitae risus ac varius. Quisque sed accumsan diam. Sed elementum eros lectus, et egestas ante hendrerit eu. Proin porta, enim nec dignissim commodo, odio orci maximus tortor, iaculis congue felis velit sed lorem. </p>',
//                    'before' => 'Before Text',
//                    'after'  => 'After Text',
//                ),
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'dates',
//            'title'  => 'Date',
//            'icon'   => 'fa fa-calendar',
//            'fields' => array(
//
//                array(
//                    'id'     => 'date_1',
//                    'type'   => 'date',
//                    'title'  => 'Date ISO',
//                    'format' => 'yy-mm-dd',
//                    'class'  => 'datepic-class',
//                    'prepend' => 'fa-calendar',
//                ),
//
//                array(
//                    'id'         => 'date_2',
//                    'type'       => 'date',
//                    'title'      => 'Date DE',
//                    'format'     => 'dd.mm.yy',
//                    'class'      => 'datepic-class',
//                    'wrap_class' => 'wrap_class',
//                ),
//
//                array(
//                    'id'     => 'date_3',
//                    'type'   => 'date',
//                    'title'  => 'Date',
//                    'format' => 'yy-mm-dd',
//                    'class'  => 'datepic-class',
//                    'picker' => 'html5',
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'title'  => esc_html__( 'Editor', 'exopite-combiner-minifier' ),
//            'icon'   => 'fa fa-paragraph',
//            'name'   => 'editors',
//            'sections' => array(
//                array(
//                    'title'  => esc_html__( 'ACE Editor', 'exopite-combiner-minifier' ),
//                    'name'   => 'editors',
//                    'icon'   => 'fa fa-code',
//                    'fields' => array(
//
//
//                        array(
//                            'id'      => 'ace_editor_1',
//                            'type'    => 'ace_editor',
//                            'title'   => 'ACE Editor',
//                            'options' => array(
//                                'theme'                     => 'ace/theme/chrome',
//                                'mode'                      => 'ace/mode/javascript',
//                                'showGutter'                => true,
//                                'showPrintMargin'           => true,
//                                'enableBasicAutocompletion' => true,
//                                'enableSnippets'            => true,
//                                'enableLiveAutocompletion'  => true,
//                            ),
//                            'attributes'    => array(
//                                'style'        => 'height: 300px; max-width: 700px;',
//                            ),
//                        ),
//
//                    ),
//                ),
//
//                array(
//                    'title'  => esc_html__( 'WYSIWYG Editors', 'exopite-combiner-minifier' ),
//                    'name'   => 'editors2',
//                    'icon'   => 'fa fa-paragraph',
//                    'fields' => array(
//
//                        array(
//                            'id'     => 'editor_1',
//                            'type'   => 'editor',
//                            'title'  => 'Editor TinyMCE',
//                        ),
//
//                        array(
//                            'id'     => 'editor_trumbowyg',
//                            'type'   => 'editor',
//                            'title'  => 'Editor Trumbowyg',
//                            'editor' => 'trumbowyg',
//                        ),
//
//                    ),
//                ),
//
//                array(
//                    'title'  => esc_html__( 'Textarea', 'exopite-combiner-minifier' ),
//                    'name'   => 'editors3',
//                    'icon'   => 'dashicons-text',
//                    'fields' => array(
//
//                        array(
//                            'id'          => 'textarea_1',
//                            'type'        => 'textarea',
//                            'title'       => 'Textarea',
//                            'attributes'    => array(
//                                'placeholder' => 'do stuff',
//                            ),
//                        ),
//
//
//                    ),
//                ),
//
//            ),
//
//        );
//
//        $fields[] = array(
//            'name'   => 'fieldsets',
//            'title'  => 'Fieldset',
//            'icon'   => 'fa fa-list-alt',
//            'fields' => array(
//
//                array(
//                    'type'    => 'fieldset',
//                    'id'      => 'fieldset_1',
//                    'title'   => esc_html__( 'Fieldset field', 'plugin-name' ),
//                    'description'   => esc_html__( 'Cols can be 1 to 4 and 6.', 'plugin-name' ) . '<br>' . esc_html__( 'Three cols per row.', 'plugin-name' ),
//                    'options' => array(
//                        'cols' => 3,
//                    ),
//                    'fields'  => array(
//
//                        array(
//                            'id'      => 'fieldset_1_text_1',
//                            'type'    => 'text',
//                            'title'   => esc_html__( 'Text', 'plugin-name' ),
//                        ),
//
//                        array(
//                            'id'             => 'fieldset_1_select_1',
//                            'type'           => 'select',
//                            'title'          => 'Miltiselect',
//                            'options'        => array(
//                                'bmw'          => 'BMW',
//                                'mercedes'     => 'Mercedes',
//                                'volkswagen'   => 'Volkswagen',
//                                'other'        => 'Other',
//                            ),
//                            'default_option' => 'Select your favorite car',
//                            'default'     => 'bmw',
//                            'attributes' => array(
//                                'multiple' => 'multiple',
//                                'style'    => 'width: 200px; height: 125px;',
//                            ),
//                            'class'       => 'chosen',
//                        ),
//
//
//                        array(
//                             'id'      => 'fieldset_1_switcher_1',
//                             'type'    => 'switcher',
//                             'title'   => esc_html__( 'Switcher', 'plugin-name' ),
//                             'default' => 'yes',
//                         ),
//                    ),
//                ),
//
//                array(
//                    'type'    => 'fieldset',
//                    'id'      => 'fieldset_2',
//                    'title'   => esc_html__( 'Fieldset field', 'plugin-name' ),
//                    'description'   => esc_html__( 'E.g.: use for border, but can be used for many things, link dimensions or spacing, etc...', 'plugin-name' ) . '<br>' . esc_html__( 'Two cols per row.', 'plugin-name' ),
//                    'options' => array(
//                        'cols' => 2,
//                    ),
//                    'fields'  => array(
//
//                        array(
//                            'id'      => 'fieldset_2_top',
//                            'type'    => 'text',
//                            'prepend' => 'fa fa-long-arrow-up',
//                            'append'  => 'px',
//                            'attributes' => array(
//                                'placeholder' => esc_html__( 'top', 'plugin-name' ),
//                            ),
//                        ),
//
//                        array(
//                            'id'      => 'fieldset_2_bottom',
//                            'type'    => 'text',
//                            'prepend' => 'fa fa-long-arrow-down',
//                            'append'  => 'px',
//                            'attributes' => array(
//                                'placeholder' => esc_html__( 'bottom', 'plugin-name' ),
//                            ),
//                        ),
//
//                        array(
//                            'id'      => 'fieldset_2_left',
//                            'type'    => 'text',
//                            'prepend' => 'fa fa-long-arrow-left',
//                            'append'  => 'px',
//                            'attributes' => array(
//                                'placeholder' => esc_html__( 'left', 'plugin-name' ),
//                            ),
//                        ),
//
//                        array(
//                            'id'      => 'fieldset_2_right',
//                            'type'    => 'text',
//                            'prepend' => 'fa fa-long-arrow-right',
//                            'append'  => 'px',
//                            'attributes' => array(
//                                'placeholder' => esc_html__( 'right', 'plugin-name' ),
//                            ),
//                        ),
//
//                        array(
//                            'id'             => 'fieldset_2_border_style',
//                            'type'           => 'select',
//                            'options'        => array(
//                                'none'          => 'None',
//                                'solid'         => 'Solid',
//                                'dashed'        => 'Dashed',
//                                'dotted'        => 'Dotted',
//                                'double'        => 'Double',
//                                'inset'         => 'Inset',
//                                'outset'        => 'Outset',
//                                'groove'        => 'Groove',
//                                'ridge'         => 'ridge',
//                            ),
//                            'default_option' => 'None',
//                            'default'     => 'none',
//                            'class'       => 'chosen width-150',
//                        ),
//
//                        array(
//                            'id'     => 'fieldset_2_color',
//                            'type'   => 'color',
//                            'rgba'   => true,
//                        ),
//
//                    ),
//                ),
//
//                array(
//                    'type'    => 'fieldset',
//                    'id'      => 'fieldset_3',
//                    'title'   => esc_html__( 'Fieldset field', 'plugin-name' ),
//                    'fields'  => array(
//
//                        array(
//                            'id'      => 'fieldset_3_text_1',
//                            'type'    => 'text',
//                            'title'   => esc_html__( 'Text', 'plugin-name' ),
//                        ),
//
//                        array(
//                            'id'             => 'fieldset_3_select_1',
//                            'type'           => 'select',
//                            'title'          => 'Miltiselect',
//                            'options'        => array(
//                                'bmw'          => 'BMW',
//                                'mercedes'     => 'Mercedes',
//                                'volkswagen'   => 'Volkswagen',
//                                'other'        => 'Other',
//                            ),
//                            'default_option' => 'Select your favorite car',
//                            'default'     => 'bmw',
//                            'attributes' => array(
//                                'multiple' => 'multiple',
//                                'style'    => 'width: 200px; height: 125px;',
//                            ),
//                            'class'       => 'chosen',
//                        ),
//
//
//                        array(
//                             'id'      => 'fieldset_3_switcher_1',
//                             'type'    => 'switcher',
//                             'title'   => esc_html__( 'Switcher', 'plugin-name' ),
//                             'default' => 'yes',
//                         ),
//                    ),
//                ),
//
//
//            ),
//        );
//
//        $fields[] = array(
//            'name'   => 'groups',
//            'title'  => 'Repeater/Sortable',
//            'icon'   => 'fa fa-object-ungroup',
//            'fields' => array(
//
//                array(
//                    'type'    => 'group',
//                    'id'      => 'group_1',
//                    'title'   => esc_html__( 'Group field nested (3 level)', 'plugin-name' ),
//                    'options' => array(
//                        'repeater'          => true,
//                        'accordion'         => true,
//                        'button_title'      => esc_html__( 'Add new (L1)', 'plugin-name' ),
//                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
//                        'limit'             => 50,
//                        'sortable'          => true,
//                    ),
//                    'fields'  => array(
//
//                        array(
//                            'id'      => 'group_1_text_1',
//                            'type'    => 'text',
//                            'title'   => esc_html__( 'Text', 'plugin-name' ),
//                            'attributes' => array(
//                                // mark this field az title, on type this will change group item title
//                                'data-title' => 'title',
//                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                            ),
//                        ),
//
//                        array(
//                            'type'    => 'group',
//                            'id'      => 'group_1_group_1',
//                            'title'   => esc_html__( 'Group field', 'plugin-name' ),
//                            'options' => array(
//                                'repeater'          => true,
//                                'accordion'         => true,
//                                'button_title'      => esc_html__( 'Add new (L2)', 'plugin-name' ),
//                                'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
//                                'limit'             => 50,
//                                'sortable'          => true,
//                            ),
//                            'fields'  => array(
//
//                                array(
//                                    'id'      => 'group_1_group_1_text_1',
//                                    'type'    => 'text',
//                                    // 'title'   => esc_html__( 'Text', 'plugin-name' ),
//                                    'attributes' => array(
//                                        // mark this field az title, on type this will change group item title
//                                        'data-title' => 'title',
//                                        'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                                    ),
//                                ),
//
//                                array(
//                                    'id'     => 'group_1_group_1_color_1',
//                                    'type'   => 'color',
//                                    'title'  => 'Color',
//                                ),
//
//                                array(
//                                    'id'     => 'group_1_group_1_date_1',
//                                    'type'   => 'date',
//                                    'title'  => 'Date ISO',
//                                    'format' => 'yy-mm-dd',
//                                    'class'  => 'datepic-class',
//                                    'prepend' => 'fa-calendar',
//                                ),
//
//
//                                array(
//                                    'id'      => 'group_1_group_1_typography_1',
//                                    'type'    => 'typography',
//                                    'title'   => esc_html__( 'Typography', 'exopite-combiner-minifier' ),
//                                    'default' => array(
//                                        'family'    =>'Arial Black',
//                                        'variant'   =>'600',
//                                        'size'      => 16,
//                                        'height'    => 24,
//                                        'color'     => '#000000',
//                                    ),
//                                    'preview' => true,
//                                ),
//                                array(
//                                    'id'             => 'group_1_group_1_select_1',
//                                    'type'           => 'select',
//                                    'title'          => 'Miltiselect',
//                                    'options'        => array(
//                                        'bmw'          => 'BMW',
//                                        'mercedes'     => 'Mercedes',
//                                        'volkswagen'   => 'Volkswagen',
//                                        'other'        => 'Other',
//                                    ),
//                                    'default_option' => 'Select your favorite car',
//                                    'default'     => 'bmw',
//                                    'attributes' => array(
//                                        'multiple' => 'multiple',
//                                        'style'    => 'width: 200px; height: 125px;',
//                                    ),
//                                    'class'       => 'chosen',
//                                ),
//
//                                array(
//                                    'type'    => 'group',
//                                    'id'      => 'group_1_group_1_group_1',
//                                    // 'title'   => esc_html__( 'Group field', 'plugin-name' ),
//                                    'options' => array(
//                                        'repeater'          => true,
//                                        'accordion'         => true,
//                                        'button_title'      => esc_html__( 'Add new (L3)', 'plugin-name' ),
//                                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
//                                        'limit'             => 50,
//                                        'sortable'          => true,
//                                    ),
//                                    'fields'  => array(
//
//                                        array(
//                                            'id'     => 'group_1_group_1_group_1_editor_trumbowyg_1',
//                                            'type'   => 'editor',
//                                            'title'  => 'Editor Trumbowyg',
//                                            'editor' => 'trumbowyg',
//                                        ),
//
//                                        array(
//                                            'id'      => 'group_1_group_1_group_1_text_1',
//                                            'type'    => 'text',
//                                            'title'   => esc_html__( 'Text', 'plugin-name' ),
//                                            'attributes' => array(
//                                                // mark this field az title, on type this will change group item title
//                                                'data-title' => 'title',
//                                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                                            ),
//                                        ),
//
//
//                                    ),
//
//                                ),
//
//                            ),
//
//                        ),
//
//                    ),
//                ),
//
//                array(
//                    'type'    => 'group',
//                    'id'      => 'group_2',
//                    'title'   => esc_html__( 'Group field', 'plugin-name' ),
//                    'options' => array(
//                        'repeater'          => true,
//                        'accordion'         => true,
//                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
//                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
//                        'limit'             => 50,
//                        'sortable'          => true,
//                    ),
//                    'fields'  => array(
//
//                        array(
//                            'id'      => 'group_2_text_1',
//                            'type'    => 'text',
//                            'title'   => esc_html__( 'Text', 'plugin-name' ),
//                            'attributes' => array(
//                                // mark this field az title, on type this will change group item title
//                                'data-title' => 'title',
//                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                            ),
//                        ),
//
//                        array(
//                            'id'      => 'group_2_switcher_1',
//                            'type'    => 'switcher',
//                            'title'   => esc_html__( 'Switcher', 'plugin-name' ),
//                            'default' => 'yes',
//                        ),
//
//                        array(
//                            'id'             => 'group_2_select_1_emails_callback',
//                            'type'           => 'select',
//                            'title'          => esc_html__( 'Users Email (callback)', 'plugin-name' ),
//                            'query'          => array(
//                                'type'          => 'callback',
//                                'function'      => array( $this, 'get_all_emails' ),
//                                'args'          => array() // WordPress query args
//                            ),
//                            'attributes' => array(
//                                'multiple' => 'multiple',
//                                'style'    => 'width: 200px; height: 56px;',
//                            ),
//                            'class'       => 'chosen',
//                        ),
//
//                        array(
//                            'id'          => 'group_2_tabbed_1',
//                            'type'        => 'tab',
//                            'title'       => esc_html__( 'Tab same with', 'plugin-name' ),
//                            'options'     => array(
//                                'equal_width' => true,
//                            ),
//                            'tabs'        => array(
//
//                                array(
//                                    'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
//                                    'icon'   => 'fa fa-star',
//                                    'fields' => array(
//
//                                        array(
//                                            'id'    => 'group_2_tab_1_text_1',
//                                            'type'  => 'text',
//                                            'title' => esc_html__( 'Tab 1 Text 1', 'plugin-name' ),
//                                        ),
//
//
//                                    ),
//                                ),
//
//                                array(
//                                    'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
//                                    'fields' => array(
//
//                                        array(
//                                            'id'    => 'group_2_tab_2_text_text_1',
//                                            'type'  => 'text',
//                                            'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                        ),
//
//                                        array(
//                                            'id'    => 'group_2_tab_2_text_2',
//                                            'type'  => 'text',
//                                            'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                        ),
//
//                                    ),
//
//                                ),
//
//                            ),
//
//                        ),
//
//                        array(
//                            'id'     => 'group_2_editor_tinymce_1',
//                            'type'   => 'editor',
//                            'title'  => 'Editor TinyMCE',
//                        ),
//
//                        array(
//                            'id'     => 'group_2_editor_trumbowyg_1',
//                            'type'   => 'editor',
//                            'title'  => 'Editor Trumbowyg',
//                            'editor' => 'trumbowyg',
//                        ),
//
//                    ),
//
//                ),
//
//                array(
//                    'type'    => 'group',
//                    'id'      => 'group_3',
//                    'title'   => esc_html__( 'Group field not an accordion', 'plugin-name' ),
//                    'options' => array(
//                        'repeater'          => true,
//                        'accordion'         => true,
//                        // 'accordion'         => false,
//                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
//                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
//                        'limit'             => 50,
//                        'sortable'          => true,
//                        'closed'            => false,
//                    ),
//                    'fields'  => array(
//
//                        array(
//                            'id'      => 'group_3_text_1',
//                            'type'    => 'text',
//                            'title'   => esc_html__( 'Text', 'plugin-name' ),
//                            'attributes' => array(
//                                // mark this field az title, on type this will change group item title
//                                'data-title' => 'title',
//                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                            ),
//                        ),
//
//                        array(
//                            'id'      => 'group_3_switcher_1',
//                            'type'    => 'switcher',
//                            'title'   => esc_html__( 'Switcher', 'plugin-name' ),
//                            'default' => 'yes',
//                        ),
//
//                        array(
//                            'id'          => 'group_3_tabbed_1',
//                            'type'        => 'tab',
//                            'title'       => esc_html__( 'Tab same with', 'plugin-name' ),
//                            'options'     => array(
//                                'equal_width' => true,
//                            ),
//                            'tabs'        => array(
//
//                                array(
//                                    'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
//                                    'icon'   => 'fa fa-star',
//                                    'fields' => array(
//
//                                        array(
//                                            'id'    => 'group_3_tab_1_text_1',
//                                            'type'  => 'text',
//                                            'title' => esc_html__( 'Tab 1 Text 1', 'plugin-name' ),
//                                        ),
//
//
//                                    ),
//                                ),
//
//                                array(
//                                    'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
//                                    'fields' => array(
//
//                                        array(
//                                            'id'    => 'group_3_tab_2_text_2',
//                                            'type'  => 'text',
//                                            'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                        ),
//
//                                        array(
//                                            'id'    => 'group_3_tab_2_text_3',
//                                            'type'  => 'text',
//                                            'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                        ),
//
//                                    ),
//
//                                ),
//
//                            ),
//
//                        ),
//
//                    ),
//
//                ),
//
//                array(
//                    'type'    => 'group',
//                    'id'      => 'group_4_sortable',
//                    'title'   => esc_html__( 'Sortable, repetable field multiple', 'plugin-name' ),
//                    'options' => array(
//                        'repeater'          => true,
//                        'accordion'         => true,
//                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
//                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
//                        'limit'             => 50,
//                        'sortable'          => true,
//                        'mode'              => 'compact',
//                    ),
//                    'fields'  => array(
//
//                        array(
//                            'id'      => 'group_4_sortable_text_1',
//                            'type'    => 'text',
//                            'attributes' => array(
//                                // mark this field az title, on type this will change group item title
//                                'data-title' => 'title',
//                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                            ),
//                        ),
//
//                        array(
//                            'id'      => 'group_4_sortable_text_2',
//                            'type'    => 'text',
//                            'attributes' => array(
//                                // mark this field az title, on type this will change group item title
//                                'data-title' => 'title',
//                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                            ),
//                        ),
//
//                    ),
//                ),
//
//                array(
//                    'type'    => 'group',
//                    'id'      => 'group_5_sortable',
//                    'title'   => esc_html__( 'Sortable (group) field single', 'plugin-name' ),
//                    'options' => array(
//                        'repeater'          => true,
//                        'accordion'         => false,
//                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
//                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
//                        'limit'             => 50,
//                        'sortable'          => true,
//                        'mode'              => 'compact', // only repeater
//                    ),
//                    'fields'  => array(
//
//                        array(
//                            'id'      => 'group_5_sortable_text_1',
//                            'type'    => 'text',
//                            'attributes' => array(
//                                // mark this field az title, on type this will change group item title
//                                'data-title' => 'title',
//                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                            ),
//                        ),
//
//
//                    ),
//                ),
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'image_selects',
//            'title'  => 'Image select',
//            'icon'   => 'fa fa-picture-o',
//            'fields' => array(
//
//
//                array(
//                    'id'        => 'image_select_1',
//                    'type'      => 'image_select',
//                    'title'     => 'Image Select Radio',
//                    'options'   => array(
//                        'value-1' => 'https://dummyimage.com/100x80/2ecc70/fff.gif&text=100x80',
//                        'value-2' => 'https://dummyimage.com/100x80/e74c3c/fff.gif&text=100x80',
//                        'value-3' => 'https://dummyimage.com/100x80/ffbc00/fff.gif&text=100x80',
//                        'value-4' => 'https://dummyimage.com/100x80/3498db/fff.gif&text=100x80',
//                        'value-5' => 'https://dummyimage.com/100x80/555555/fff.gif&text=100x80',
//                    ),
//                    'radio'        => true,
//                    'default'      => 'value-2',
//                ),
//
//                array(
//                    'id'        => 'image_select_2',
//                    'type'      => 'image_select',
//                    'title'     => 'Image Select Checkbox',
//                    'options'   => array(
//                        'value-1' => 'https://dummyimage.com/100x80/2ecc70/fff.gif&text=100x80',
//                        'value-2' => 'https://dummyimage.com/100x80/e74c3c/fff.gif&text=100x80',
//                        'value-3' => 'https://dummyimage.com/100x80/ffbc00/fff.gif&text=100x80',
//                        'value-4' => 'https://dummyimage.com/100x80/3498db/fff.gif&text=100x80',
//                        'value-5' => 'https://dummyimage.com/100x80/555555/fff.gif&text=100x80',
//                    ),
//                    'default'      => 'value-3',
//                    'description' => 'This is a longer description with <a href="#">link</a> to explain what this field for.<br><i>You can use any HTML here.</i>',
//                ),
//
//                array(
//                    'id'          => 'image_select_3',
//                    'type'        => 'image_select',
//                    'title'       => 'Image Select Radio Vertical',
//                    'options'     => array(
//                        'value-1'   => 'https://dummyimage.com/450x70/2ecc70/fff.gif&text=450x70',
//                        'value-2'   => 'https://dummyimage.com/450x70/e74c3c/fff.gif&text=450x70',
//                        'value-3'   => 'https://dummyimage.com/450x70/ffbc00/fff.gif&text=450x70',
//                        'value-4'   => 'https://dummyimage.com/450x70/3498db/fff.gif&text=450x70',
//                        'value-5'   => 'https://dummyimage.com/450x70/555555/fff.gif&text=450x70',
//                    ),
//                    'default'     => 'value-4',
//                    'layout'      => 'vertical',
//                    'radio'       => true,
//                    'description' => esc_html__( 'Vertical layot, could be used for e.g. header styles.', 'plugin-name' ),
//                ),
//
//            ),
//        );
//
//        $fields[] = array(
//            'name'   => 'notices',
//            'title'  => 'Notice',
//            'icon'   => 'fa fa-exclamation-circle',
//            'fields' => array(
//
//                array(
//                    'type'    => 'notice',
//                    'class'   => 'info',
//                    'content' => 'This is info notice field for your highlight sentence.',
//                ),
//
//                array(
//                    'type'    => 'notice',
//                    'class'   => 'primary',
//                    'content' => 'This is info notice field for your highlight sentence.',
//                ),
//
//                array(
//                    'type'    => 'notice',
//                    'class'   => 'secondary',
//                    'content' => 'This is info notice field for your highlight sentence.',
//                ),
//
//                array(
//                    'type'    => 'notice',
//                    'class'   => 'success',
//                    'content' => 'This is info notice field for your highlight sentence.',
//                ),
//
//                array(
//                    'type'    => 'notice',
//                    'class'   => 'warning',
//                    'content' => 'This is info notice field for your highlight sentence.',
//                ),
//
//                array(
//                    'type'    => 'notice',
//                    'class'   => 'danger',
//                    'content' => 'This is info notice field for your highlight sentence.',
//                ),
//
//            ),
//        );
//
//        $fields[] = array(
//            'name'   => 'numbers',
//            'title'  => 'Number',
//            'icon'   => 'fa fa-sliders',
//            'fields' => array(
//
//                array(
//                    'id'      => 'number_1',
//                    'type'    => 'number',
//                    'title'   => 'Number',
//                    'default' => '10',
//                    // 'unit'    => '$',
//                    'after'   => ' <i class="text-muted">$ (dollars)</i>',
//                    'min'     => '2',
//                    'max'     => '20',
//                    'step'    => '2',
//                ),
//
//                array(
//                    'id'      => 'range_1',
//                    'type'    => 'range',
//                    'title'   => 'range',
//                    'default' => '10',
//                    // 'unit'    => '$',
//                    'after'   => ' <i class="text-muted">$ (dollars)</i>',
//                    'min'     => '2',
//                    'max'     => '20',
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'tab',
//            'title'  => 'Tab',
//            'icon'   => 'fa fa-folder',
//            'fields' => array(
//
//                array(
//                    'id'          => 'tabbed_1',
//                    'type'        => 'tab',
//                    'title'       => esc_html__( 'Nested Tabs with same width', 'plugin-name' ),
//                    'options'     => array(
//                        'equal_width' => true,
//                    ),
//                    'tabs'        => array(
//
//                        array(
//                            'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
//                            'icon'   => 'fa fa-star',
//                            'fields' => array(
//
//
//                                    array(
//                                        'id'          => 'tabbed_1_tab1',
//                                        'type'        => 'tab',
//                                        'title'       => esc_html__( 'Tab same width', 'plugin-name' ),
//                                        'options'     => array(
//                                            'equal_width' => true,
//                                        ),
//                                        'tabs'        => array(
//
//                                            array(
//                                                'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
//                                                'icon'   => 'fa fa-star',
//                                                'fields' => array(
//
//                                                    array(
//                                                        'id'    => 'tabbed_1_tab_1_text_1',
//                                                        'type'  => 'text',
//                                                        'title' => esc_html__( 'Tab 1 Text 1', 'plugin-name' ),
//                                                    ),
//
//                                                ),
//                                            ),
//
//                                            array(
//                                                'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
//                                                'fields' => array(
//
//                                                    array(
//                                                        'id'    => 'tabbed_1_tab_1_text_2',
//                                                        'type'  => 'text',
//                                                        'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                                    ),
//
//                                                    array(
//                                                        'id'    => 'tabbed_1_tab_1_text_3',
//                                                        'type'  => 'text',
//                                                        'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                                    ),
//
//                                                ),
//
//                                            ),
//
//                                        ),
//
//                                    ),
//
//
//                            ),
//                        ),
//
//                        array(
//                            'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
//                            'fields' => array(
//
//                                array(
//                                    'id'    => 'tabbed_1_tab_2_text_1',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                ),
//
//                                array(
//                                    'id'    => 'tabbed_1_tab_2_text_2',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                ),
//
//                                array(
//                                    'type'    => 'group',
//                                    'id'      => 'tabbed_1_tab_2_group_1',
//                                    'title'   => esc_html__( 'Group field', 'plugin-name' ),
//                                    'options' => array(
//                                        'repeater'          => true,
//                                        'accordion'         => true,
//                                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
//                                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
//                                        'limit'             => 50,
//                                        'sortable'          => true,
//                                    ),
//                                    'fields'  => array(
//
//                                        array(
//                                            'id'      => 'tabbed_1_tab_2_group_1_text_1',
//                                            'type'    => 'text',
//                                            'title'   => esc_html__( 'Text', 'plugin-name' ),
//                                            'attributes' => array(
//                                                // mark this field az title, on type this will change group item title
//                                                'data-title' => 'title',
//                                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
//                                            ),
//                                        ),
//
//                                        array(
//                                            'id'      => 'tabbed_1_tab_2_group_1_switcher_1',
//                                            'type'    => 'switcher',
//                                            'title'   => esc_html__( 'Switcher', 'plugin-name' ),
//                                            'default' => 'yes',
//                                        ),
//
//                                    ),
//
//                                ),
//
//                            ),
//
//                        ),
//
//                    ),
//
//                ),
//
//                array(
//                    'id'          => 'tabbed_2',
//                    'type'        => 'tab',
//                    'title'       => esc_html__( 'Tab same width', 'plugin-name' ),
//                    'options'     => array(
//                        'equal_width' => true,
//                    ),
//                    'tabs'        => array(
//
//                        array(
//                            'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
//                            'icon'   => 'fa fa-star',
//                            'fields' => array(
//
//                                array(
//                                    'id'    => 'tabbed_2_tab_1_text_1',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Tab 1 Text 1', 'plugin-name' ),
//                                ),
//
//                                array(
//                                    'id'      => 'tabbed_2_tab_1_ace_editor_1',
//                                    'type'    => 'ace_editor',
//                                    'title'   => esc_html__( 'Tab 1 ACE Editor 1', 'plugin-name' ),
//                                    'options' => array(
//                                        'theme'                     => 'ace/theme/chrome',
//                                        'mode'                      => 'ace/mode/javascript',
//                                        'showGutter'                => true,
//                                        'showPrintMargin'           => true,
//                                        'enableBasicAutocompletion' => true,
//                                        'enableSnippets'            => true,
//                                        'enableLiveAutocompletion'  => true,
//                                    ),
//                                    'attributes'    => array(
//                                        'style'        => 'height: 300px; max-width: 700px;',
//                                    ),
//                                ),
//
//                            ),
//                        ),
//
//                        array(
//                            'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
//                            'fields' => array(
//
//                                array(
//                                    'id'    => 'tabbed_2_tab_1_text_2',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                ),
//
//                                array(
//                                    'id'    => 'tabbed_2_tab_1_text_3',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
//                                ),
//
//                            ),
//
//                        ),
//
//                    ),
//
//                ),
//
//                array(
//                    'id'          => 'tabbed_3',
//                    'type'        => 'tab',
//                    'title'       => esc_html__( 'Tab', 'plugin-name' ),
//                    'options'     => array(
//                        'equal_width' => false,
//                    ),
//                    'tabs'        => array(
//
//                        array(
//                            'title'  => esc_html__( 'Tab 3', 'plugin-name' ),
//                            'icon'   => 'fa fa-star',
//                            'fields' => array(
//
//                                array(
//                                    'id'    => 'tabbed_3_tab_1_text_1',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Tab 3 Text 1', 'plugin-name' ),
//                                ),
//
//                                array(
//                                    'id'      => 'tabbed_3_tab_1_ace_editor_1',
//                                    'type'    => 'ace_editor',
//                                    'title'   => esc_html__( 'Tab 3 ACE Editor 1', 'plugin-name' ),
//                                    'options' => array(
//                                        'theme'                     => 'ace/theme/chrome',
//                                        'mode'                      => 'ace/mode/javascript',
//                                        'showGutter'                => true,
//                                        'showPrintMargin'           => true,
//                                        'enableBasicAutocompletion' => true,
//                                        'enableSnippets'            => true,
//                                        'enableLiveAutocompletion'  => true,
//                                    ),
//                                    'attributes'    => array(
//                                        'style'        => 'height: 300px; max-width: 700px;',
//                                    ),
//                                ),
//
//                            ),
//                        ),
//
//                        array(
//                            'title'  => esc_html__( 'Tab 4', 'plugin-name' ),
//                            'fields' => array(
//
//                                array(
//                                    'id'    => 'tabbed_3_tab_1_text_2',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Tab 4 Text 1', 'plugin-name' ),
//                                ),
//
//                                array(
//                                    'id'    => 'tabbed_3_tab_1_text_3',
//                                    'type'  => 'text',
//                                    'title' => esc_html__( 'Tab 4 Text 2', 'plugin-name' ),
//                                ),
//
//                            ),
//
//                        ),
//
//                    ),
//
//                ),
//
//
//            )
//        );
//
//       $fields[] = array(
//            'name'   => 'tap_lists',
//            'title'  => 'Tap List',
//            'icon'   => 'fa fa-hand-pointer-o',
//            'fields' => array(
//
//
//                array(
//                    'id'        => 'tap_list_1',
//                    'type'      => 'tap_list',
//                    'title'     => 'Tap list Radio',
//                    'options'   => array(
//                        'value-1' => 'First',
//                        'value-2' => 'Second',
//                        'value-3' => 'Third',
//                        'value-4' => 'Forth',
//                        'value-5' => 'Fifth',
//                    ),
//                    'radio'        => true,
//                    'default'      => 'value-3',
//                ),
//
//                array(
//                    'id'        => 'tap_list_2',
//                    'type'      => 'tap_list',
//                    'title'     => 'Tap list Checkbox',
//                    'options'   => array(
//                        'value-1' => 'First',
//                        'value-2' => 'Second',
//                        'value-3' => 'Third',
//                        'value-4' => 'Forth',
//                        'value-5' => 'Fifth',
//                    ),
//                    'default'      => array(
//                        'value-2',
//                        'value-3'
//                    ),
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'typography_section',
//            'title'  => 'Typography',
//            'icon'   => 'fa fa-font',
//            'fields' => array(
//
//                array(
//                    'id'      => 'typography_1',
//                    'type'    => 'typography',
//                    'title'   => esc_html__( 'Typography', 'exopite-combiner-minifier' ),
//                    'default' => array(
//                        'family'    =>'Arial Black',
//                        'variant'   =>'600',
//                        'size'      => 16,
//                        'height'    => 24,
//                        'color'     => '#000000',
//                    ),
//                    'preview' => true,
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'selects',
//            'title'  => 'Select',
//            'icon'   => 'fa fa-list-ul',
//            'fields' => array(
//
//                array(
//                    'id'             => 'select_1',
//                    'type'           => 'select',
//                    'title'          => 'Select',
//                    'options'        => array(
//                        'bmw'          => 'BMW',
//                        'mercedes'     => 'Mercedes',
//                        'volkswagen'   => 'Volkswagen',
//                        'other'        => 'Other',
//                    ),
//                    'default_option' => 'Select your favorite car',
//                    'default'     => 'bmw',
//                ),
//
//                array(
//                    'id'             => 'select_2',
//                    'type'           => 'select',
//                    'title'          => 'Select Chosen',
//                    'options'        => array(
//                        'bmw'          => 'BMW',
//                        'mercedes'     => 'Mercedes',
//                        'volkswagen'   => 'Volkswagen',
//                        'other'        => 'Other',
//                    ),
//                    'default_option' => 'Select your favorite car',
//                    'default'     => 'bmw',
//                    'class'       => 'chosen',
//                    'prepend' => 'dashicons-arrow-down-alt',
//                ),
//
//                array(
//                    'id'             => 'select_3',
//                    'type'           => 'select',
//                    'title'          => 'Select Chosen',
//                    'options'        => array(
//                        'bmw'          => 'BMW',
//                        'mercedes'     => 'Mercedes',
//                        'volkswagen'   => 'Volkswagen',
//                        'other'        => 'Other',
//                    ),
//                    'default_option' => 'Select your favorite car',
//                    'default'     => 'bmw',
//                    'class'       => 'chosen',
//                    'append' => 'dashicons-admin-tools',
//                ),
//
//                array(
//                    'id'             => 'select_4',
//                    'type'           => 'select',
//                    'title'          => 'Miltiselect',
//                    'options'        => array(
//                        'bmw'          => 'BMW',
//                        'mercedes'     => 'Mercedes',
//                        'volkswagen'   => 'Volkswagen',
//                        'other'        => 'Other',
//                    ),
//                    'default_option' => 'Select your favorite car',
//                    'default'     => 'bmw',
//                    'attributes' => array(
//                        'multiple' => 'multiple',
//                        'style'    => 'width: 200px; height: 125px;',
//                    ),
//                    'class'       => 'chosen',
//                ),
//
//                array(
//                    'id'             => 'select_5',
//                    'type'           => 'select',
//                    'title'          => 'Select Chosen Posts',
//                    // 'options'        => 'posts',
//                    'query'          => array(
//                        'type'           => 'posts',
//                        'args'           => array(
//                            'orderby'      => 'post_date',
//                            'order'        => 'DESC',
//                        ),
//                    ),
//                    'default_option' => '',
//                    'class'       => 'chosen',
//                ),
//
//
//                array(
//                    'id'             => 'select_6',
//                    'type'           => 'select',
//                    'title'          => 'Select Chosen Pages',
//                    // 'options'        => 'pages',
//                    'query'          => array(
//                        'type'           => 'pages',
//                        'args'           => array(
//                            'orderby'      => 'post_date',
//                            'order'        => 'DESC',
//                        ),
//                    ),
//                    'default_option' => '',
//                    'class'       => 'chosen',
//                ),
//
//                /**
//                 * Options via callback function,
//                * options settings will be ignored
//                */
//                array(
//                    'id'             => 'select_7',
//                    'type'           => 'select',
//                    'title'          => 'Title',
//                    'query'          => array(
//                        'type'          => 'callback',
//                        'function'      => array( $this, 'get_all_emails' ),
//                        'args'          => array() // WordPress query args
//                    ),
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'video',
//            'title'  => 'Video',
//            'icon'   => 'fa fa-youtube-play',
//            'fields' => array(
//
//                array(
//                    'id'            => 'video_1',
//                    'type'          => 'video',
//                    'title'         => 'Video oEmbed',
//                    // 'default'       => '/wp-content/uploads/2018/01/video.mp4',
//                    // - OR for oEmbed: -
//                    'default'       => 'https://www.youtube.com/watch?v=KujZ__rrs0k',
//                    'info'          => 'oEmbed',
//                    'attributes'    => array(
//                        'placeholder'   => 'oEmbed',
//                    ),
//                    'options'       => array(
//                        'input'         => false,
//                        'oembed'        => true,
//                    ),
//                ),
//
//                array(
//                    'id'            => 'video_2',
//                    'type'          => 'video',
//                    'title'         => 'Video oEmbed',
//                    // 'default'       => '/wp-content/uploads/2018/01/video.mp4',
//                    // - OR for oEmbed: -
//                    'default'       => 'https://www.youtube.com/watch?v=KujZ__rrs0k',
//                    'info'          => 'oEmbed',
//                    'attributes'    => array(
//                        'placeholder'   => 'oEmbed',
//                    ),
//                    'options'       => array(
//                        'input'         => true,
//                        'oembed'        => true,
//                    ),
//                ),
//
//
//            )
//        );
//
//        $fields[] = array(
//            'name'   => 'upload',
//            'title'  => 'Upload',
//            'icon'   => 'fa fa-upload',
//            'fields' => array(
//
//
//                array(
//                    'id'      => 'upload_1',
//                    'type'    => 'upload',
//                    'title'   => 'Upload',
//                    'options' => array(
//                        'attach'                    => true, // attach to post (only in metabox)
//                        'filecount'                 => '101',
//                        // 'allowed'                   => array( 'png', 'jpeg' ),
//                        // 'delete-enabled'            => false,
//                        // 'delete-force-confirm'      => true,
//                        // 'retry-enable-auto'         => true,
//                        // 'retry-max-auto-attempts'   => 3,
//                        // 'retry-auto-attempt-delay'  => 3,
//                        // 'auto-upload'               => false,
//                    ),
//                ),
//
//
//            )
//	    );

	    /**
	     * instantiate your admin page
	     */
	    $options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );

	}
    public function notes_meta_meta_boxes(){
        
    }
	public function custom_meta_boxes(){
        //if( isset($_GET['post'])){


		$post_ID = isset($_GET['post']) ? sanitize_text_field( $_GET['post'] ) : 0;
		$post_type = '';
		$notes = array();
		$tasks = array();
        $custom_fields = array();

		if(($post_ID)){
			$post = get_post($post_ID);
			$post_type = $post->post_type;
		

        $custom_fields_data = get_post_meta( $post_ID, 'custom_fields', true);
	
        if( is_array( $custom_fields_data ) ) {
        	foreach ($custom_fields_data as $custom_field_key => $custom_field_value) {
            $custom_fields[] = array(
                	'id'          => 'custom_field]['.$custom_field_key,
                    'type'        => 'text',
                    'title'       => $custom_field_key,
                    'class'       => 'text-class',
                    'default'     => $custom_field_value,
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                  ),
            );
        	}
        	// echo "<pre>";
        	// print_r( $custom_fields );
        	// die();

        }
        }

		if($post_type !== ''){
			/********************************************************************
				Get notes relations on single-edit screen
			********************************************************************/

			$notes_posts = $this->get_swell_posts('-1', NULL, 'date', 'DESC', $post_type . '_id', get_post_meta($post_ID, 'hash_id', true), 'note', $filters = true);

			if(!empty($notes_posts)){
				foreach($notes_posts as $note){
					$notes[] = array(
	                    'type'    => 'card',
	                    'class'   => 'class-notes', // for all fieds
                        'id'      => $note->ID,
	                    'title'   => $note->post_title,
	                    'content' => $note->post_content,
                        'post_type' => $note->post_type,
	                    'header' => $note->post_title,
	                    'footer' => $note->post_title,
	                );
				}
			} else {
				$notes[] = array(
                    'type'    => 'card',
                    'class'   => 'class-notes', // for all fieds
                    'title'   => 'It looks like there is nothing here yet.',
                    'content' => 'Start by creating a note',
                    'header' => 'Header Text',
                    'footer' => 'Footer Text',
                    // 'post_type' => 'note',
                    // 'post_id' => $post_ID,
                );
			}

			/********************************************************************
				Get tasks relations on single-edit screen
			********************************************************************/
			$tasks_posts = $this->get_swell_posts('-1', NULL, 'date', 'DESC', $post_type . '_id', get_post_meta($post_ID, 'hash_id', true), 'task', $filters = true);

			if(!empty($tasks_posts)){
				foreach($tasks_posts as $task){
					$tasks[] = array(
	                    'type'    => 'card',
	                    'class'   => 'class-tasks', // for all fieds
                        'id'      => $task->ID,
	                    'title'   => $task->post_title,
                        'post_type' => $task->post_type,
	                    'content' => $task->post_content,
	                    'header' => $task->post_title,
	                    'footer' => $task->post_title,
	                );
				}
			} else {
				$tasks[] = array(
                    'type'    => 'card',
                    'class'   => 'class-tasks', // for all fieds
                    'title'   => 'It looks like there is nothing here yet.',
                    'content' => 'Start by creating a task',
                    'header' => 'Header Text',
                    'footer' => 'Footer Text',
                    // 'post_type' => 'task',
                    // 'post_id' => $post_ID,
                );
			}
		}

		/*
		 * To add a metabox.
		 * This normally go to your functions.php or another hook
		 */
		$config_metabox = array(
        		    /*
        		     * METABOX
        		     */
        		    'type'              => 'metabox',
        		    'id'                => $this->plugin_name . '-meta',
        		    'post_types'        => array( 'client', 'lead', 'contact' ),    // Post types to display meta box
        		    'context'           => 'advanced',
        		    'priority'          => 'default',
        		    'title'             => 'SWELLEnterprise Details',
        		    'capability'        => '',              // The capability needed to view the page
        		    // 'tabbed'            => false,                  // Add tabs or not, default true
        		    // 'simple'            => true,                   // Save post meta as simple insted of an array, default false
        		    // 'multilang'         => true,                   // Multilang support, required for ONLY qTranslate-X and WP Multilang
        		                                                      // for WPML and Polilang leave it in default.
        		                                                      // default: false
		        );


		$fields[] = array(
		    'name'   => 'first',
		    'title'  => 'Details',
		    'fields' => array(

		        // fields...

                array(
                    'id'          => 'first_name',
                    'type'        => 'text',
                    'title'       => 'First Name',
                    'class'       => 'text-class',
                    'description' => 'First Name',
                    'default'     => get_post_meta( $post_ID, 'first_name', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

                array(
                    'id'          => 'last_name',
                    'type'        => 'text',
                    'title'       => 'Last Name',
                    'class'       => 'text-class',
                    'description' => 'Last Name',
                    'default'     => get_post_meta( $post_ID, 'last_name', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

                array(
                    'id'          => 'organization',
                    'type'        => 'text',
                    'title'       => 'Organization',
                    'class'       => 'text-class',
                    'description' => 'Organization',
                    'default'     => get_post_meta( $post_ID, 'organization', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

                array(
                    'id'          => 'email',
                    'type'        => 'text',
                    'title'       => 'Email',
                    'class'       => 'text-class',
                    'description' => 'Enter a valid email address',
                    'default'     => get_post_meta( $post_ID, 'email', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

                array(
                    'id'          => 'phone',
                    'type'        => 'text',
                    'title'       => 'Phone',
                    'class'       => 'text-class',
                    'description' => 'Enter a valid phone number',
                    'default'     => get_post_meta( $post_ID, 'phone_number', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

                array(
                    'id'          => 'address',
                    'type'        => 'text',
                    'title'       => 'Address',
                    'class'       => 'text-class',
                    'description' => 'Enter a valid address',
                    'default'     => get_post_meta( $post_ID, 'address', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

                array(
                    'id'          => 'city',
                    'type'        => 'text',
                    'title'       => 'City',
                    'class'       => 'text-class',
                    'description' => 'Enter a valid city',
                    'default'     => get_post_meta( $post_ID, 'city', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

                array(
                    'id'          => 'state',
                    'type'        => 'text',
                    'title'       => 'State',
                    'class'       => 'text-class',
                    'description' => 'Enter a valid state',
                    'default'     => get_post_meta( $post_ID, 'state', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

                array(
                    'id'          => 'zip',
                    'type'        => 'text',
                    'title'       => 'Zip Code',
                    'class'       => 'text-class',
                    'description' => 'Enter a valid zip code',
                    'default'     => get_post_meta( $post_ID, 'zip', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',
                       'required'    => 'true',
                    ),
                ),

		    ),
		);
        
		$fields[] = array(
		    'name'   => 'notes',
		    'title'  => 'Notes',
		    'fields' => $notes,
		);

		$fields[] = array(
		    'name'   => 'tasks',
		    'title'  => 'Tasks',
		    'fields' => $tasks,
		);

        $fields[] = array(
            'name'   => 'custom_fields',
            'title'  => 'Custom Fields',
            'fields' => $custom_fields,
        );



		$metabox_panel = new Exopite_Simple_Options_Framework( $config_metabox, $fields );
      //  }
	}

    public function tasks_custom_meta_boxes() {
      // if( isset( $_GET['post'] )){
        $post_ID = isset($_GET['post']) ?  sanitize_text_field( $_GET['post'] ) : 0;
        $post_type = '';
        if(($post_ID)){
            $post = get_post($post_ID);
            $post_type = $post->post_type;
        }
        // echo "<pre>";
        // print_r( get_post_meta( $post_ID ) );
        $config_metabox = array(
                    /*
                     * METABOX
                     */
                    'type'              => 'metabox',
                    'id'                => $this->plugin_name . '-meta',
                    'post_types'        => array( 'task' ),    // Post types to display meta box
                    'context'           => 'advanced',
                    'priority'          => 'default',
                    'title'             => 'SWELLEnterprise Details',
                    'capability'        => 'edit_posts',              // The capability needed to view the page
                    // 'tabbed'            => false,                  // Add tabs or not, default true
                    // 'simple'            => true,                   // Save post meta as simple insted of an array, default false
                    // 'multilang'         => true,                   // Multilang support, required for ONLY qTranslate-X and WP Multilang
                                                                      // for WPML and Polilang leave it in default.
                                                                      // default: false
                );
        $fields[] = array(
            'name'   => 'first',
            'title'  => 'Task Details',
            'class'  => 'task-details',
            'fields' => array(
                array(
                    'id'          => 'start_date',
                    'type'        => 'date',
                    'title'       => 'Start Date',
                    'class'       => 'text-class',
                    'description' => 'Start Date',
                    'default'     => get_post_meta( $post_ID, 'start_date', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',

                    ),
                ),

                array(
                    'id'          => 'end_date',
                    'type'        => 'date',
                    'title'       => 'End Date',
                    'class'       => 'text-class',
                    'description' => 'End Date',
                    'default'     => get_post_meta( $post_ID, 'end_date', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',

                    ),
                ),
                array(
                    'id'          => 'start_time',
                    'type'        => 'time',
                    'title'       => 'Start Time',
                    'class'       => 'text-class',
                    'description' => 'Start Time',
                    'default'     => get_post_meta( $post_ID, 'start_time', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',

                    ),
                ),
                array(
                    'id'          => 'end_time',
                    'type'        => 'time',
                    'title'       => 'End Time',
                    'class'       => 'text-class',
                    'description' => 'End Time',
                    'default'     => get_post_meta( $post_ID, 'end_time', true ),
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => '',
                       'data-test'   => 'test',

                    ),
                ),

            ),
        );

        $metabox_panel = new Exopite_Simple_Options_Framework( $config_metabox, $fields );
       // }
    }

	public function get_swell_posts($num = '-1', $category = NULL, $orderby = 'date', $order = 'DESC', $meta_key = NULL, $meta_value = NULL, $post_type, $filters = true){
		if( !empty( $meta_value)){


		$args = array(
			'numberposts'      => $num,
	        'category'         => $category,
	        'orderby'          => $orderby,
	        'order'            => $order,
	        'include'          => array(),
	        'exclude'          => array(),
	        'meta_key'         => $meta_key,
	        'meta_value'       => $meta_value,
	        'post_type'        => $post_type,
	        'suppress_filters' => $filters,
        );

        $posts = get_posts($args);

        return $posts;
        }
	}

	/*function swellenterprise_filter_posts_columns( $columns ) {
		$columns['first_name'] = __( 'First Name', $this->plugin_name );
		$columns['last_name'] = __( 'Last Name', $this->plugin_name );
		$columns['organization'] = __( 'Organization', $this->plugin_name );
		return $columns;
	}

	function swellenterprise_lead_column( $column, $post_id ) {

		// First name column
		if ( 'first_name' === $column ) {
			$first_name = get_post_meta( $post_id, 'first_name', true );

			if ( ! $first_name ) {
				_e( 'n/a' );  
			} else {
				echo esc_attr( $first_name );
			}
		}

		// Last name column
		if ( 'last_name' === $column ) {
			$last_name = get_post_meta( $post_id, 'last_name', true );

			if ( ! $last_name ) {
				_e( 'n/a' );  
			} else {
				echo esc_attr( $last_name );
			}
		}

		// Organization name column
		if ( 'organization' === $column ) {
			$organization = get_post_meta( $post_id, 'organization', true );

			if ( ! $organization ) {
				_e( 'n/a' );  
			} else {
				echo esc_attr( $organization );
			}
		}
	  
	}
*/
	/**
	 * Modify columns in tests list in admin area.
	 */
	public function lead_posts_columns( $columns ) {

	    // Remove unnecessary columns
	    unset(
	        $columns['author'],
	        $columns['comments']
	    );

	    // Rename title and add ID and Address
	    $columns['first_name'] = esc_attr__( 'First Name', $this->plugin_name );
	    $columns['last_name'] = esc_attr__( 'Last Name', $this->plugin_name );
	    $columns['organization'] = esc_attr__( 'Organization', $this->plugin_name );


	    /**
	     * Rearrange column order
	     *
	     * Now define a new order. you need to look up the column
	     * names in the HTML of the admin interface HTML of the table header.
	     *
	     *     "cb" is the "select all" checkbox.
	     *     "title" is the title column.
	     *     "date" is the date column.
	     *     "icl_translations" comes from a plugin (eg.: WPML).
	     *
	     * change the order of the names to change the order of the columns.
	     *
	     * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
	     */
	    // $customOrder = array('cb', 'first_name', 'last_name', 'organization', 'taxonomy-lead_tag', 'taxonomy-lead_status', 'date');
	    // $customOrder = array('cb', 'first_name', 'last_name', 'organization', 'taxonomy-lead_tag', 'date');
	    $customOrder = array('cb', 'first_name', 'last_name', 'organization', 'date');

	    // -- OR --
	    // https://crunchify.com/how-to-move-wordpress-admin-column-before-or-after-any-other-column-manage-post-columns-hook/

	    /**
	     * return a new column array to wordpress.
	     * order is the exactly like you set in $customOrder.
	     */
	    foreach ($customOrder as $column_name)
	        $rearranged[$column_name] = $columns[$column_name];

	    return $rearranged;

	}

	/**
	 * Modify columns in tests list in admin area.
	 */
	public function contact_posts_columns( $columns ) {

	    // Remove unnecessary columns
	    unset(
	        $columns['author'],
	        $columns['comments']
	    );

	    // Rename title and add ID and Address
	    $columns['first_name'] = esc_attr__( 'First Name', $this->plugin_name );
	    $columns['last_name'] = esc_attr__( 'Last Name', $this->plugin_name );
	    $columns['organization'] = esc_attr__( 'Organization', $this->plugin_name );


	    /**
	     * Rearrange column order
	     *
	     * Now define a new order. you need to look up the column
	     * names in the HTML of the admin interface HTML of the table header.
	     *
	     *     "cb" is the "select all" checkbox.
	     *     "title" is the title column.
	     *     "date" is the date column.
	     *     "icl_translations" comes from a plugin (eg.: WPML).
	     *
	     * change the order of the names to change the order of the columns.
	     *
	     * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
	     */
	    // $customOrder = array('cb', 'first_name', 'last_name', 'organization', 'taxonomy-contact_tag', 'date');
	    $customOrder = array('cb', 'first_name', 'last_name', 'organization', 'date');

	    // -- OR --
	    // https://crunchify.com/how-to-move-wordpress-admin-column-before-or-after-any-other-column-manage-post-columns-hook/

	    /**
	     * return a new column array to wordpress.
	     * order is the exactly like you set in $customOrder.
	     */
	    foreach ($customOrder as $column_name)
	        $rearranged[$column_name] = $columns[$column_name];

	    return $rearranged;

	}

	/**
	 * Modify columns in tests list in admin area.
	 */
	public function client_posts_columns( $columns ) {

	    // Remove unnecessary columns
	    unset(
	        $columns['author'],
	        $columns['comments']
	    );

	    // Rename title and add ID and Address
	    $columns['first_name'] = esc_attr__( 'First Name', $this->plugin_name );
	    $columns['last_name'] = esc_attr__( 'Last Name', $this->plugin_name );
	    $columns['organization'] = esc_attr__( 'Organization', $this->plugin_name );


	    /**
	     * Rearrange column order
	     *
	     * Now define a new order. you need to look up the column
	     * names in the HTML of the admin interface HTML of the table header.
	     *
	     *     "cb" is the "select all" checkbox.
	     *     "title" is the title column.
	     *     "date" is the date column.
	     *     "icl_translations" comes from a plugin (eg.: WPML).
	     *
	     * change the order of the names to change the order of the columns.
	     *
	     * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
	     */
	    // $customOrder = array('cb', 'first_name', 'last_name', 'organization', 'taxonomy-client_tag', 'date');
	    $customOrder = array('cb', 'first_name', 'last_name', 'organization', 'date');

	    // -- OR --
	    // https://crunchify.com/how-to-move-wordpress-admin-column-before-or-after-any-other-column-manage-post-columns-hook/

	    /**
	     * return a new column array to wordpress.
	     * order is the exactly like you set in $customOrder.
	     */
	    foreach ($customOrder as $column_name)
	        $rearranged[$column_name] = $columns[$column_name];

	    return $rearranged;

	}

	// Callback function of adding filter in acf fields
	public function my_acf_load_field( $field ) {
		$address = get_option('swell_client.create_webhook');
    	$field['default_value'] = $address;
    	return $field;
	}

	// Populate new columns in customers list in admin area
	public function manage_posts_custom_column( $column, $post_id ) {

	    // For array, not simple options
	    // global $post;
	    // $custom = get_post_custom();
	    // $meta = maybe_unserialize( $custom[$this->plugin_name][0] );

	    // Populate column form meta
	    switch ($column) {

	        case "first_name":
	        	if(!empty(get_post_meta( $post_id, $column, true ))){
		            echo '<a href="' . get_edit_post_link() . '">';
		            echo get_post_meta( $post_id, $column, true );
		            echo '</a>';
		        } else {
		        	echo 'N/A';
		        }
	            break;
	        case "last_name":
	            if(!empty(get_post_meta( $post_id, $column, true ))){
		            echo '<a href="' . get_edit_post_link() . '">';
		            echo get_post_meta( $post_id, $column, true );
		            echo '</a>';
		        } else {
		        	echo 'N/A';
		        }
	            break;
	        case "organization":
	        	if(!empty(get_post_meta( $post_id, $column, true ))){
		            echo '<a href="' . get_edit_post_link() . '">';
		            echo get_post_meta( $post_id, $column, true );
		            echo '</a>';
		        } else {
		        	echo 'N/A';
		        }
	            break;
	        // case "some_column":
	        //     // For array, not simple options
	        //     echo $meta["some_column"];
	        //     break;

	    }

	}

	public function add_style_to_admin_head() {
	    global $post_type;
	    $screen = get_current_screen();
	    //var_dump($screen);
	    if ( $screen->base == 'settings_page_swellenterprise') {
	    	?>
	    	<style type="text/css">
                * {
				  box-sizing: border-box;
				}
				.col-1 {width: 8.33%;}
				.col-2 {width: 16.66%;}
				.col-3 {width: 25%;}
				.col-4 {width: 33.33%;}
				.col-5 {width: 41.66%;}
				.col-6 {width: 50%;}
				.col-7 {width: 58.33%;}
				.col-8 {width: 66.66%;}
				.col-9 {width: 75%;}
				.col-10 {width: 83.33%;}
				.col-11 {width: 91.66%;}
				.col-12 {width: 100%;}
				[class*="col-"] {
				  float: left;
				  padding: 15px;
				}
				.row::after {
				  content: "";
				  clear: both;
				  display: table;
				}
				a.btn{
				    padding: 20px 10px;
				    background: #06458d;
				    margin: 20px auto;
				    display: inline-block;
				    color: #fff;
				    text-decoration: none;
				    font-weight: bold;
				}
				a.btn:hover {background: #4894de;}
            </style>
	    <?php
		}
	    if ( 'lead' == $post_type ) {
	        ?>
	            <style type="text/css">
	                .column-thumbnail {
	                    width: 80px !important;
	                }
	                .column-title {
	                    width: 30% !important;
	                }
	            </style>
	        <?php
	    }
	}

	/**
	 * To sort, Exopite Simple Options Framework need 'options' => 'simple'.
	 * Simple options is stored az induvidual meta key, value pair, otherwise it is stored in an array.
	 *
	 *
	 * Meta key value paars need to sort as induvidual.
	 *
	 * I implemented this option because it is possible to search in serialized (array) post meta:
	 * @link https://wordpress.stackexchange.com/questions/16709/meta-query-with-meta-values-as-serialize-arrays
	 * @link https://stackoverflow.com/questions/15056407/wordpress-search-serialized-meta-data-with-custom-query
	 * @link https://www.simonbattersby.com/blog/2013/03/querying-wordpress-serialized-custom-post-data/
	 *
	 * but there is no way to sort them with wp_query or SQL.
	 * @link https://wordpress.stackexchange.com/questions/87265/order-by-meta-value-serialized-array/87268#87268
	 * "Not in any reliable way. You can certainly ORDER BY that value but the sorting will use the whole serialized string,
	 * which will give * you technically accurate results but not the results you want. You can't extract part of the string
	 * for sorting within the query itself. Even if you wrote raw SQL, which would give you access to database functions like
	 * SUBSTRING, I can't think of a dependable way to do it. You'd need a MySQL function that would unserialize the value--
	 * you'd have to write it yourself.
	 * Basically, if you need to sort on a meta_value you can't store it serialized. Sorry."
	 *
	 * It is possible to get all required posts and store them in an array and then sort them as an array,
	 * but what if you want multiple keys/value pair to be sorted?
	 *
	 * UPDATE
	 * it is maybe possible:
	 * @link http://www.russellengland.com/2012/07/how-to-unserialize-data-using-mysql.html
	 * but it is waaay more complicated and less documented as meta query sort and search.
	 * It should be not an excuse to use it, but it is not as reliable as it should be.
	 *
	 * @link https://wpquestions.com/Order_by_meta_key_where_value_is_serialized/7908
	 * "...meta info serialized is not a good idea. But you really are going to lose the ability to query your
	 * data in any efficient manner when serializing entries into the WP database.
	 *
	 * The overall performance saving and gain you think you are achieving by serialization is not going to be noticeable to
	 * any major extent. You might obtain a slightly smaller database size but the cost of SQL transactions is going to be
	 * heavy if you ever query those fields and try to compare them in any useful, meaningful manner.
	 *
	 * Instead, save serialization for data that you do not intend to query in that nature, but instead would only access in
	 * a passive fashion by the direct WP API call get_post_meta() - from that function you can unpack a serialized entry
	 * to access its array properties too."
	 */
	public function manage_sortable_columns( $columns ) {

	    $columns['first_name'] = 'first_name';
	    $columns['last_name'] = 'last_name';
	    $columns['organization'] = 'organization';

	    return $columns;

	}

	public function manage_posts_orderby( $query ) {

	    if( ! is_admin() || ! $query->is_main_query() ) {
	        return;
	    }

	    /**
	     * meta_types:
	     * Possible values are 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'.
	     * Default value is 'CHAR'.
	     *
	     * @link https://codex.wordpress.org/Class_Reference/WP_Meta_Query
	     */
	    $columns = array(
	        'first_name'  => 'char',
	        'last_name' => 'char',
	        'organization'  => 'char',
	    );

	    foreach ( $columns as $key => $type ) {

	        if ( $key === $query->get( 'orderby') ) {
	            $query->set( 'orderby', 'meta_value' );
	            $query->set( 'meta_key', $key );
	            $query->set( 'meta_type', $type );
	            break;
	        }

	    }

	}


	public function swell_admin_notice($error = null, $message = null){
        //global $pagenow;
        //if ( $pagenow == 'options-general.php' ) {
        if($error !== NULL && $message !== NULL){
			$this->error = $error;
			$this->message = $message;
             echo '<div class="notice notice-' . $this->error . ' is-dismissible">
                 <p>' . $this->message . '</p>
                 <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
             </div>';
         }
		$this->error = "";
		$this->message = "";
        //}
    }

}