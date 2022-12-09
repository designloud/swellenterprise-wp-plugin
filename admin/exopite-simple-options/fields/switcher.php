<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Switcher
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_switcher' ) ) {

	class Exopite_Simple_Options_Framework_Field_switcher extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			echo $this->element_before();
			$label = ( isset( $this->field['label'] ) ) ? '<div class="exopite-sof-text-desc">' . esc_attr( $this->field['label'] ) . '</div><span class="sync_sucess swell_sync_success_'. esc_attr( $this->field['title'] ) .'"></span><button class="buttonload button_sync_'. esc_attr( $this->field['title'] ) .'">
  <i class="fa fa-refresh fa-spin"></i> Syncing '. esc_attr( $this->field['title'] ).'
</button><a class="swell-sync" data-sync="'. esc_attr( $this->field['title'] ) .'">Sync '. esc_attr( $this->field['title'] ) .'</a>' : '';

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			echo '<label class="checkbox">';
			echo '<input name="' . esc_attr( $this->element_name() ) . '" value="yes" class="checkbox__input ' . esc_attr( $classes ) . '" type="checkbox"' . esc_attr( $this->element_attributes() ) . checked( $this->element_value(), 'yes', false ) . '>';
			echo '<div class="checkbox__switch"></div>';
			echo '</label>' . wp_kses_post($label);
			echo $this->element_after();

		}

	}

}
