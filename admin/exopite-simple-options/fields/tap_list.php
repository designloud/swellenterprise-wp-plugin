<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Tap List
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_tap_list' ) ) {

	class Exopite_Simple_Options_Framework_Field_tap_list extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$input_type = ( ! empty( $this->field['radio'] ) ) ? 'radio' : 'checkbox';
			$input_attr = ( $input_type == 'checkbox' ) ? '[]' : '';

			echo esc_attr( $this->element_before() );

			if ( isset( $this->field['options'] ) ) {

				echo esc_html( '<ul class="list">' );

				$options = $this->field['options'];
				foreach ( $options as $key => $value ) {

					echo esc_html( '<li class="list__item list__item--tappable">' );
					echo esc_html( '<div class="list__item__left">' );

					if ( $input_type == 'radio' ) {
						# code...
					}

					switch ( $input_type ) {
						case 'radio':
							echo esc_html( '<label class="radio-button">' );
							echo esc_html('<input type="' . esc_attr( $input_type ) . '" name="' . esc_attr( $this->element_name( $input_attr ) ) . '" class="radio-button__input" id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $value ) . '" value="' . esc_attr( $key ) . '"' . esc_attr( $this->element_attributes( $key ) ) . $this->checked( $this->element_value(), esc_attr( $key ) ) . '/>');
							echo esc_html( '<div class="radio-button__checkmark"></div>' );
							echo esc_html( '</label>' );
							break;

						case 'checkbox':
							echo esc_html( '<label class="checkbox checkbox--noborder">' );
							echo esc_html('<input type="' . esc_attr( $input_type ) . '" name="' . esc_attr( $this->element_name( $input_attr ) ) . '" class="checkbox__input checkbox--noborder__input" id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $value ) . '" value="' . esc_attr( $key ) . '"' . $this->element_attributes( $key ) . $this->checked( $this->element_value(), esc_attr( $key ) ) . '>');
							echo esc_html( '<div class="checkbox__checkmark checkbox--noborder checkbox--noborder__checkmark"></div>' );
							echo esc_html( '</label>' );
							break;
					}


					echo esc_html( '</div>' );
					echo esc_html('<label for="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $value ) . '" class="list__item__center">');
					echo esc_attr( $value );
					echo esc_html('</label>');
					echo esc_html( '</li>' );

				}

				echo esc_html( '</ul>' );
			}

			echo esc_attr( $this->element_after() );

		}

	}

}
