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
            echo esc_html( '<div class="notice notice-success is-dismissible"><p><strong>' . get_transient( 'exopite_sof_updated_message' ) . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>' );

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
                    'attributes'    => array(
                        'rows'        => 10,
                        'cols'        => 5,
                    ),
                ),

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
            ),

        );

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
                    'id'          => 'custom_field]['.esc_attr( $custom_field_key ),
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
    }

    public function tasks_custom_meta_boxes() {
        $post_ID = isset($_GET['post']) ?  sanitize_text_field( $_GET['post'] ) : 0;
        $post_type = '';
        if(($post_ID)){
            $post = get_post($post_ID);
            $post_type = $post->post_type;
        }
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

        // Populate column form meta
        switch ($column) {

            case "first_name":
                if(!empty(get_post_meta( $post_id, $column, true ))){
                    $first_name = get_post_meta( $post_id, $column, true );
                    echo '<a href="' . get_edit_post_link() . '">';
                    echo esc_attr( $first_name );
                    echo '</a>';
                } else {
                    echo 'N/A';
                }
                break;
            case "last_name":
                if(!empty(get_post_meta( $post_id, $column, true ))){
                    $last_name = get_post_meta( $post_id, $column, true );
                    echo '<a href="' . get_edit_post_link() . '">';
                    echo esc_attr( $last_name );
                    echo '</a>';
                } else {
                    echo 'N/A';
                }
                break;
            case "organization":
                if(!empty(get_post_meta( $post_id, $column, true ))){
                    $organization = get_post_meta( $post_id, $column, true );
                    echo '<a href="' . get_edit_post_link() . '">';
                    echo esc_attr( $organization );
                    echo '</a>';
                } else {
                    echo 'N/A';
                }
                break;

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
             echo esc_html( '<div class="notice notice-' . esc_attr( $this->error ) . ' is-dismissible">
                 <p>' . esc_attr( $this->message ) . '</p>
                 <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
             </div>' );
         }
        $this->error = "";
        $this->message = "";
        //}
    }

}