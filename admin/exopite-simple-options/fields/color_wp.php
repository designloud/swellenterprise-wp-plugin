<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Color
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_color_wp' ) ) {

	class Exopite_Simple_Options_Framework_Field_color_wp extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {

			parent::__construct( $field, $value, $unique, $config, $multilang );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			/*
			 * Color Picker
			 *
			 * @link https://paulund.co.uk/adding-a-new-color-picker-with-wordpress-3-5
			 */

			echo esc_attr( $this->element_before() );
			echo esc_html( '<input type="text" class="colorpicker ' . esc_attr( $classes ) . '" ' );
			if ( isset( $this->field['rgba'] ) && $this->field['rgba'] ) {
				echo esc_str( 'data-alpha="true" ' );
			}
			echo esc_html('name="' . esc_attr( $this->element_name() ) . '" value="' . esc_attr( $this->element_value() ) . '"');
			echo esc_html( esc_attr( $this->element_attributes() ). '/>' );

		}

		public static function enqueue( $args ) {

			// Add the color picker css file from WordPress
			wp_enqueue_style( 'wp-color-picker' );

			$resources = array(
				array(
					'name'       => 'wp-color-picker-alpha',
					'fn'         => 'wp-color-picker-alpha.min.js',
					'type'       => 'script',
					'dependency' => array( 'wp-color-picker' ),
					'version'    => '2.1.3',
					'attr'       => true,
				),
				array(
					'name'       => 'exopite-sof-wp-color-picker-loader',
					'fn'         => 'loader-color-picker.min.js',
					'type'       => 'script',
					'dependency' => array( 'wp-color-picker-alpha' ),
					'version'    => '20190407',
					'attr'       => true,
				),
			);

			parent::do_enqueue( $resources, $args );

		}

	}

}
