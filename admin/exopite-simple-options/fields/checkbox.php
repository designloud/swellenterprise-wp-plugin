<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Checkbox
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_checkbox' ) ) {

	class Exopite_Simple_Options_Framework_Field_checkbox extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {

			parent::__construct( $field, $value, $unique, $config );

		}

		public function output() {

			echo esc_attr( $this->element_before() );
			$label = ( isset( $this->field['label'] ) ) ? esc_attr( $this->field['label'] ) : '';
			$style = ( isset( $this->field['style'] ) ) ? esc_attr( $this->field['style'] ): '';

			switch ( $style ) {
				case 'fancy':
					echo esc_html('<label class="checkbox">');
					echo esc_html('<input type="checkbox" class="checkbox__input" name="' . esc_attr( $this->element_name() ) . '" value="yes"' . $this->element_attributes() . checked( $this->element_value(), 'yes', false ) . '>');
					echo esc_html('<div class="checkbox__checkmark"></div>');
					echo esc_attr( $label );
					echo esc_html('</label>');
					break;

				default:
					echo esc_html('<label><input type="checkbox" name="' . $this->element_name() . '" value="yes"' . $this->element_class() . $this->element_attributes() . checked( $this->element_value(), 'yes', false ) . '/> ' . $label . '</label>');
					break;
			}


			echo esc_attr( $this->element_after() );

		}

	}

}
