<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Color
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_color' ) ) {

	class Exopite_Simple_Options_Framework_Field_color extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {

			parent::__construct( $field, $value, $unique, $config, $multilang );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';
			$controls = array( 'hue', 'brightness', 'saturation', 'wheel' );
			$control = ( isset( $this->field['control'] ) ) ? $this->field['control'] : 'saturation';
			$formats = array( 'rgb', 'hex' );
			$format = ( isset( $this->field['format'] ) ) ? $this->field['format'] : 'rgb';

			echo esc_attr( $this->element_before() );
			echo esc_html( '<input type="' );
			if ( isset( $this->field['picker'] ) && $this->field['picker'] == 'html5' ) {
				echo esc_attr( 'color' ) ;
			} else {
				echo esc_attr( 'text' );
			}
			echo esc_html('" ');
			if ( ! isset( $this->field['picker'] ) || $this->field['picker'] != 'html5' ) {
				echo esc_html( 'class="minicolor ' . esc_attr( $classes ) . '" ' );
			}
			if ( isset( $this->field['rgba'] ) && $this->field['rgba'] ) {
				echo esc_html('data-opacity="1" ' );
			}
			if ( in_array( $control, $controls ) ) {
				echo esc_html( 'data-control="' . esc_attr( $control ) . '" ' ); // hue, brightness, saturation, wheel
			}
			if ( in_array( $format, $formats ) ) {
				echo esc_html( 'data-format="' . esc_attr( $format ) . '" '); // hue, brightness, saturation, wheel
			}
			echo esc_html( 'name="' . $this->element_name() . '" value="' . $this->element_value() . '"' );
			if ( isset( $this->field['picker'] ) && $this->field['picker'] == 'html5' ) {
				echo esc_attr( $this->element_class() );
			}
			echo esc_html( esc_attr( $this->element_attributes() ) . '/>');

		}

		public static function enqueue( $args ) {

			$resources = array(
				array(
					'name'       => 'minicolors_css',
					'fn'         => 'jquery.minicolors.css',
					'type'       => 'style',
					'dependency' => array(),
					'version'    => '20181201',
					'attr'       => 'all',
				),
				array(
					'name'       => 'minicolors_js',
					'fn'         => 'jquery.minicolors.js',
					'type'       => 'script',
					'dependency' => array( 'jquery' ),
					'version'    => '20181201',
					'attr'       => true,
				),
				array(
					'name'       => 'minicolors-loader',
					'fn'         => 'loader-minicolors.js',
					'type'       => 'script',
					'dependency' => array( 'minicolors_js' ),
					'version'    => '20190407',
					'attr'       => true,
				),
			);

			parent::do_enqueue( $resources, $args );

		}

	}

}
