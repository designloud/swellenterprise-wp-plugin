<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Tab
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_accordion' ) ) {

	class Exopite_Simple_Options_Framework_Field_accordion extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {
			parent::__construct( $field, $value, $unique, $config, $multilang );

			$this->defaults = array(
				'section_title'  	=> esc_attr( 'Section Title', 'swellenterprise' ),
				'closed'       		=> true,
				'allow_all_open'	=> true,
			);

			$this->is_accordion_closed = ( isset( $this->field['options']['closed'] ) ) ? (bool) $this->field['options']['closed'] : $this->defaults['closed'];
			$this->allow_all_open = ( isset( $this->field['options']['allow_all_open'] ) ) ? (bool) $this->field['options']['allow_all_open'] : $this->defaults['allow_all_open'];

		}

		public function output() {

			echo esc_attr( $this->element_before() );

			$unallows 	= array( 'accordion' );
			$sections   = array_values( $this->field['sections'] );
			$unique_id 	= ( ! empty( $this->unique ) ) ? $this->unique : $this->field['id'];


			$self  = new Exopite_Simple_Options_Framework( array(
				'id' => $this->element_name(),
				'multilang' => $this->config['multilang'],
				'is_options_simple' => $this->config['is_options_simple'],
			), null );

			echo esc_html('<div class="exopite-sof-accordion">');

			echo esc_html('<div class="exopite-sof-accordion__wrapper" data-all-open="' . esc_attr( $this->allow_all_open ) . '">');

			/**
			 * Accordion items
			 */
			foreach ( $sections as $key => $section ) {

				$is_section_closed = ( isset( $section['options']['closed'] ) ) ? (bool) esc_atr( $section['options']['closed'] ) : esc_attr( $this->defaults['closed'] );
				$section_title = ( isset( $section['options']['title'] ) ) ? esc_attr( $section['options']['title'] ) : esc_attr( $this->defaults['section_title'] );

				$muster_classes = array();
				if ( $is_section_closed && $this->is_accordion_closed ) {
					$muster_classes[] = 'exopite-sof-accordion--hidden';
				}

				echo esc_html('<div class="exopite-sof-cloneable__item exopite-sof-accordion__item ' . implode( ' ', $muster_classes ) . '">');

				echo esc_html('<h4 class="exopite-sof-cloneable__title exopite-sof-accordion__title">' . esc_attr( $section_title ) . '</h4>');

				echo esc_html('<div class="exopite-sof-accordion__content">');

				foreach ( $section['fields'] as $field ) {

					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true;
						continue;
					}

					$self->include_field_class( array( 'type' => $field['type'] ) );
					$self->enqueue_field_class( array( 'type' => $field['type'] ) );

					$field_value = '';
					if ( isset( $this->value[ $field['id'] ] ) ) {
						$field_value = $this->value[ $field['id'] ];
					} elseif ( isset( $field['default'] ) ) {
						$field_value = $field['default'];
					}

					echo esc_attr( $self->add_field( $field, $field_value ) );

				}

				echo esc_html('</div>'); // exopite-sof-accordion__content
				echo esc_html('</div>'); // exopite-sof-accordion__title


			}

			echo esc_html('</div>'); // exopite-sof-accordion__wrapper
			echo esc_html('</div>'); // exopite-sof-accordion

			echo esc_attr( $this->element_after() );

		}

	}

}
