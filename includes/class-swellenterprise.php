<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SWELLEnterprise
 * @subpackage SWELLEnterprise/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    SWELLEnterprise
 * @subpackage SWELLEnterprise/includes
 * @author     Your Name <email@example.com>
 */
class SWELLEnterprise {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      SWELLEnterprise_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $swellenterprise    The string used to uniquely identify this plugin.
     */
    protected $swellenterprise;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /*     * ***********************************************************
     * ACCESS PLUGIN AND ITS METHODES LATER FROM OUTSIDE OF PLUGIN
     *
     * @tutorial access_plugin_and_its_methodes_later_from_outside_of_plugin.php
     */

    /**
     * Store plugin admin class to allow public access.
     *
     * @since    20180622
     * @var object      The admin class.
     */
    public $admin;

    /**
     * Store plugin public class to allow public access.
     *
     * @since    20180622
     * @var object      The admin class.
     */
    public $public;
    // END ACCESS PLUGIN AND ITS METHODES LATER FROM OUTSIDE OF PLUGIN

    /*     * ***********************************************************
     * ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
     *
     * @tutorial access_plugin_admin_public_methodes_from_inside.php
     */

    /**
     * Store plugin main class to allow public access.
     *
     * @since    20180622
     * @var object      The main class.
     */
    public $main;

    // ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('SWELLENTERPRISE_VERSION')) {
            $this->version = SWELLENTERPRISE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->swellenterprise = 'swellenterprise';

        $this->load_dependencies();
        $this->set_locale();

        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - SWELLEnterprise_Loader. Orchestrates the hooks of the plugin.
     * - SWELLEnterprise_i18n. Defines internationalization functionality.
     * - SWELLEnterprise_Admin. Defines all hooks for the admin area.
     * - SWELLEnterprise_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * Exopite Simple Options Framework
         *
         * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
         * @author Joe Szalai
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/exopite-simple-options/exopite-simple-options-framework-class.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-swellenterprise-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-swellenterprise-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-swellenterprise-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-swellenterprise-public.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-swellenterprise-post_types.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-swellenterprise-api.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-swellenterprise-api-services.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-swellenterprise-restapis.php';

        $this->loader = new SWELLEnterprise_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the SWELLEnterprise_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new SWELLEnterprise_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new SWELLEnterprise_Admin($this->get_swellenterprise(), $this->get_version());
        $plugin_post_types = new SWELLEnterprise_Post_Types();
        $plugin_api = new SWELLEnterprise_API();
        $plugin_services = new SWELLEnterprise_API_Services();
        $SWELLEnterprise_RestApis = new SWELLEnterprise_RestApis();

        

        /*         * ***********************************************************
         * ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
         * (COMBINED WITH ACCESS PLUGIN AND ITS METHODES LATER FROM OUTSIDE OF PLUGIN)
         *
         *
         * @tutorial access_plugin_admin_public_methodes_from_inside.php
         */

        $this->admin = new SWELLEnterprise_Admin($this->get_swellenterprise(), $this->get_version(), $this->main);
        $this->loader->add_action('admin_enqueue_scripts', $this->admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $this->admin, 'enqueue_scripts');
        $this->loader->add_action('init', $this->admin, 'create_menu', 999);
        $this->loader->add_action('init', $this->admin, 'custom_meta_boxes', 999);
        $this->loader->add_action('init', $this->admin, 'tasks_custom_meta_boxes', 999);
        $this->loader->add_action('add_meta_boxes', $SWELLEnterprise_RestApis, 'task_status_meta_box' );
        $this->loader->add_action('add_meta_boxes', $SWELLEnterprise_RestApis, 'lead_status_meta_box' );

        $this->loader->add_action('init', $SWELLEnterprise_RestApis, 'swell_save_task_statuses', 999 );
        $this->loader->add_action('init', $SWELLEnterprise_RestApis, 'swell_save_lead_statuses', 999 );
        $this->loader->add_action('init', $SWELLEnterprise_RestApis, 'swell_save_lead_custom_fields', 999 );
        $this->loader->add_action('init', $SWELLEnterprise_RestApis, 'swell_save_contact_custom_fields', 999 );
        $this->loader->add_action('init', $SWELLEnterprise_RestApis, 'swell_save_client_custom_fields', 999 );
        $this->loader->add_action('wp_ajax_swell_get_data', $plugin_services, 'swell_get_data', 999);
        $this->loader->add_action('rest_api_init', $SWELLEnterprise_RestApis, 'register_routes', 999);
        $this->loader->add_action('wp_trash_post', $SWELLEnterprise_RestApis, 'swell_trash_post', 10, 2);
        $this->loader->add_action('edit_form_after_editor', $SWELLEnterprise_RestApis, 'swell_edit_form_callback', 10, 1);
        $this->loader->add_action('save_post', $SWELLEnterprise_RestApis, 'swell_get_post_data', 10, 3);
        $this->loader->add_filter( 'http_request_timeout', $SWELLEnterprise_RestApis, 'increase_request_http_request_timeout_filter', 10, 2 );
        /**
         * The problem with the initial activation code is that when the activation hook runs, it's after the init hook has run,
         * so hooking into init from the activation hook won't do anything.
         * You don't need to register the CPT within the activation function unless you need rewrite rules to be added
         * via flush_rewrite_rules() on activation. In that case, you'll want to register the CPT normally, via the
         * loader on the init hook, and also re-register it within the activation function and
         * call flush_rewrite_rules() to add the CPT rewrite rules.
         *
         * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/261
         */
        $this->loader->add_action('init', $plugin_post_types, 'create_custom_post_type', 999);

        

        //$this->loader->add_action('init', $plugin_api, 'init_services', 999);


        $this->loader->add_action('init', $plugin_api, 'registerWebhooks', 999);


        $this->loader->add_action( 'init', $plugin_services, 'sync_alldata', 999 );

        /**
         * This function runs when WordPress completes its upgrade process
         * It iterates through each plugin updated to see if ours is included
         * @param $upgrader_object Array
         * @param $options Array
         */
        $this->loader->add_action('upgrader_process_complete', $this->admin, 'upgrader_process_complete', 10, 2);

        /**
         * Show a notice to anyone who has just updated this plugin
         * This notice shouldn't display to anyone who has just installed the plugin for the first time
         */
        $this->loader->add_action('admin_notices', $this->admin, 'display_update_notice');
        $this->loader->add_action('admin_notices', $this->admin, 'swell_admin_notice');

        /**
         * Modify columns in tests list in admin area.
         *
         * The hooks to create custom columns and their associated data for a custom post type
         * are manage_{$post_type}_posts_columns and
         * manage_{$post_type}_{$post_type_type}_custom_column or manage_{$post_type_hierarchical}_custom_column respectively,
         * where {$post_type} is the name of the custom post type and {$post_type_hierarchical} is post or page.
         *
         * @link https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
         * @link https://wordpress.stackexchange.com/questions/253640/adding-custom-columns-to-custom-post-types/253644#253644
         */
        $this->loader->add_filter('manage_lead_posts_columns', $this->admin, 'lead_posts_columns');
        $this->loader->add_filter('manage_client_posts_columns', $this->admin, 'client_posts_columns');
        $this->loader->add_filter('manage_contact_posts_columns', $this->admin, 'contact_posts_columns');
        $this->loader->add_action('manage_posts_custom_column', $this->admin, 'manage_posts_custom_column', 10, 2);
        $this->loader->add_action('admin_head', $this->admin, 'add_style_to_admin_head');

        /**
         * Filters the list table sortable columns for a specific screen.
         * manage_{$this->screen->id}_sortable_columns
         *
         * @link https://developer.wordpress.org/reference/hooks/manage_this-screen-id_sortable_columns/
         * @link https://www.smashingmagazine.com/2017/12/customizing-admin-columns-wordpress/
         */
        $this->loader->add_filter('manage_edit-lead_sortable_columns', $this->admin, 'manage_sortable_columns');
        $this->loader->add_action('pre_get_posts', $this->admin, 'manage_posts_orderby');

        /* Adding filter for custom fields */

        $this->loader->add_filter('acf/load_field/name=client_create_webhook', $this->admin, 'my_acf_load_field');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new SWELLEnterprise_Public($this->get_swellenterprise(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_swellenterprise() {
        return $this->swellenterprise;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    SWELLEnterprise_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
