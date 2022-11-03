<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Video
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_video' ) ) {

	class Exopite_Simple_Options_Framework_Field_video extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {

			parent::__construct( $field, $value, $unique, $config );

			$defaults = array(
				'input'    => true,
				'oembed'   => false,
				'url'      => '',
				'loop'     => '',
				'autoplay' => '',
				'muted'    => 'muted',
				'controls' => 'controls'
			);

			$options                = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();
			$this->field['options'] = wp_parse_args( $options, $defaults );

		}

		public function output() {

			echo esc_attr( $this->element_before() );

			echo esc_html('<div class="exopite-sof-media exopite-sof-video exopite-sof-video-container"' . esc_attr( $this->element_class() ) . '><div class="video-wrap">');

			/**
			 * If user want only to display a video (without input field), will be never saved,
			 * because no input. So if value is empty end input is disabled, display default.
			 */
			$video_url = '';
			if ( empty( $this->element_value() ) && ( isset( $this->field['options']['input'] ) && false == $this->field['options']['input'] ) && isset( $this->field['default'] ) ) {
				$video_url = isset( $this->field['default'] );
			} else {
				$video_url = isset( $this->element_value() );
			}

			if ( $this->field['options']['oembed'] ) {

				echo wp_oembed_get( $video_url );

			} else {

				$video_atts = array(
					$this->field['options']['loop'],
					$this->field['options']['autoplay'],
					$this->field['options']['muted'],
					$this->field['options']['controls']
				);

				echo esc_html('<video class="video-control" ' . implode( ' ', $video_atts ) . ' src="' . esc_url( $video_url ) . '"></video>');

			}

			echo esc_html( '</div>' );

			if ( $this->field['options']['input'] ) {
				echo esc_html( '<div class="exopite-sof-video-input">' );
				echo esc_html('<input type="text" name="' . esc_str( $this->element_name() ) . '" value="' . esc_attr( $this->element_value() ) . '"' . esc_attr( $this->element_attributes() ) . '/>');

				if ( ! $this->field['options']['oembed'] ) {

					echo esc_html('<a href="#" class="button button-primary exopite-sof-button">' . esc_attr__( 'Add Video', 'exopite-sof' ) . '</a>');

				}
				echo esc_html( '</div>' );
			}

			echo esc_html( '</div>' );

			echo esc_attr( $this->element_after() );

		}

	}

}
