<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Textarea
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_meta' ) ) {

	class Exopite_Simple_Options_Framework_Field_meta extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			if ( $this->config['type'] != 'metabox') {

				echo esc_str( 'This item only available in metabox!<br>' );

			} else {

				if ( ! empty( $this->field['meta-key'] ) ) {

					$value = get_post_meta( get_the_ID(), esc_attr( $this->field['meta-key'] ), true );
					$value = sanitize_text_field( $value );
					echo esc_attr( $this->element_before() );
					echo esc_html( '<textarea readonly' . esc_attr( $this->element_class() ) . esc_attr( $this->element_attributes() ) . '>' . var_export( $value, true ) . '</textarea>' );
					echo esc_attr( $this->element_after() );
				}

			}


		}


	}

}
