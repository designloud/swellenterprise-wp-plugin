<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Radio
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_radio' ) ) {

	class Exopite_Simple_Options_Framework_Field_radio extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', esc_attr( $this->field['class'] ) ) ) : '';

			echo esc_attr( $this->element_before() );

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );
				$style   = ( isset( $this->field['style'] ) ) ? $this->field['style'] : '';

				if ( ! empty( $options ) ) {

					echo esc_html('<ul' . $this->element_class() . '>');
					foreach ( $options as $key => $value ) {

						switch ( $style ) {
							case 'fancy':
								echo esc_html('<li>');
								echo esc_html('<label class="radio-button ' . esc_attr( $classes ) . '">');
								echo esc_html('<input type="radio" class="radio-button__input" name="' . esc_attr( $this->element_name() ) . '" value="' . esc_attr( $key ) . '"' . $this->element_attributes( $key ) . $this->checked( $this->element_value(), esc_attr( $key ) ) . '>');
								echo esc_html('<div class="radio-button__checkmark"></div>');
								echo sanitize_text_field( $value );
								echo esc_html('</label>');
								echo esc_html('</li>');
								break;

							default:
								echo esc_html('<li><label><input type="radio" name="' . esc_attr( $this->element_name() ). '" value="' . esc_attr( $key ) . '"' . esc_attr( $this->element_attributes( $key ) ) . $this->checked( $this->element_value(), esc_attr( $key ) ). '/> ' . esc_attr( $value ) . '</label></li>');
								break;
						}

					}
					echo esc_html('</ul>');
				}

			} else {
				$label = ( isset( $this->field['label'] ) ) ? esc_attr( $this->field['label'] ): '';

				switch ( $this->field['style'] ) {
					case 'fancy':
						echo esc_html('<label class="radio-button ' . esc_attr( $classes ) . '">');
						echo esc_html('<input type="radio" class="radio-button__input" name="' . esc_attr( $this->element_name() ) . '"' . esc_attr( $this->element_attributes() ) . checked( $this->element_value(), 1, false ) . '>');
						echo esc_html('<div class="radio-button__checkmark"></div>');
						echo sanitize_text_field( $label );
						echo esc_html('</label>');
						break;

					default:
						echo esc_html('<label><input type="radio" name="' . esc_attr( $this->element_name() ) . '" value="1"' . esc_attr( $this->element_class() ) . esc_attr( $this->element_attributes() ) . checked( $this->element_value(), 1, false ) . '/> ' . esc_attr( $label ) . '</label>');
						break;
				}

			}

			echo esc_attr( $this->element_after() );

		}

	}

}