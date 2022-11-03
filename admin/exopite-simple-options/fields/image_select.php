<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Image Select
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_image_select' ) ) {

	class Exopite_Simple_Options_Framework_Field_image_select extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$input_type = ( ! empty( $this->field['radio'] ) ) ? 'radio' : 'checkbox';
			$input_attr = ( $input_type == 'checkbox' ) ? '[]' : '';
			$layout = ( isset( $this->field['layout'] ) && $this->field['layout'] == 'vertical' ) ? 'exopite-sof-field-image-selector-vertical' : 'exopite-sof-field-image-selector-horizontal';

			echo esc_attr( $this->element_before() );
			echo esc_html( '<div class="exopite-sof-field-image-selector ' . esc_attr( $layout ) . '">' );

			if ( isset( $this->field['options'] ) ) {
				$options = esc_attr( $this->field['options'] );
				foreach ( $options as $key => $value ) {
					echo esc_html('<label><input type="' . esc_attr( $input_type ) . '" name="' . esc_attr( $this->element_name( $input_attr ) ) . '" value="' . esc_attr( $key ). '"' . esc_attr( $this->element_class() ) . esc_attr( $this->element_attributes( $key ) ) . $this->checked( $this->element_value(), esc_attr( $key ) ) . '/>');
					echo ( ! empty( $this->field['text_select'] ) ) ? esc_html('<span class="exopite-sof-' . sanitize_title( $value ) . '">' . sanitize_title( $value ) . '</span>') : esc_html('<img src="' . sanitize_title( $value ) . '"   alt="' . sanitize_title( $key ) . '" />');
					echo esc_html( '</label>' );
				}
			}

			echo esc_html( '</div>' );
			echo esc_attr( $this->element_after() );

		}

	}

}
