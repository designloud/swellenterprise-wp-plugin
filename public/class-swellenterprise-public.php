<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SWELLEnterprise
 * @subpackage SWELLEnterprise/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SWELLEnterprise
 * @subpackage SWELLEnterprise/public
 * @author     Your Name <email@example.com>
 */
class SWELLEnterprise_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $swellenterprise       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $swellenterprise, $version ) {

		$this->swellenterprise = $swellenterprise;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->swellenterprise, plugin_dir_url( __FILE__ ) . 'css/swellenterprise-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->swellenterprise, plugin_dir_url( __FILE__ ) . 'js/swellenterprise-public.js', array( 'jquery' ), $this->version, false );

	}

}
