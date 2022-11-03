<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Button
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_button' ) ) {

	class Exopite_Simple_Options_Framework_Field_button extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {

			parent::__construct( $field, $value, $unique, $config );

			$defaults = array(
				'href'      => '',
				'target'    => '_self',
				'value'     => 'button',
                'btn-class' => 'exopite-sof-btn',
                'btn-id'    => '',
			);

			$options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

			$this->field['options'] = wp_parse_args( $options, $defaults );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			echo esc_attr( $this->element_before() );
            echo esc_html( '<a ' );
            if ( ! empty( $this->field['options']['href'] ) ) {
                echo esc_html( 'href="' . $this->field['options']['href'] . '"' );
            }
            if ( ! empty( $this->field['options']['btn-id'] ) ) {
                echo esc_html( ' id="' . $this->field['options']['btn-id'] . '"' );
            }
            echo esc_html( ' target="' . esc_attr( $this->field['options']['target'] ) . '"' );
            echo esc_html( ' class="' . esc_attr( $this->field['options']['btn-class'] ) . ' ' . esc_attr( $classes ) . '"' );
            echo esc_html( esc_attr( $this->element_attributes() ) . '/>' . esc_attr( $this->field['options']['value'] ) . '</a>' );
            echo esc_attr( $this->element_after() );

		}

	}

}
