<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Notice
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_notice' ) ) {

	class Exopite_Simple_Options_Framework_Field_notice extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			$content = ( isset( $this->field['content'] ) ) ? esc_attr( $this->field['content'] ) : '';

			if ( isset( $this->field['callback'] ) ) {

				$callback = $this->field['callback'];
				if ( is_callable( $callback['function'] ) ) {

					$args    = ( isset( $callback['args'] ) ) ? $callback['args'] : '';
					$content = call_user_func( $callback['function'], $args );

				}
			}

			echo esc_attr( $this->element_before() );
			echo esc_html('<div class="exopite-sof-notice ' . esc_attr( $classes ) . '">' . esc_attr( $content ) . '</div>');
			echo esc_attr( $this->element_after() );

		}

	}

}
