<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_backup' ) ) {
	class Exopite_Simple_Options_Framework_Field_backup extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			echo esc_attr( $this->element_before() );

			if ( $this->config['type'] == 'menu' ) {

				$nonce   = wp_create_nonce( 'exopite_sof_backup' );
				$options = get_option( $this->unique );
				$export  = esc_url( add_query_arg( array(
					'action'  => 'exopite-sof-export-options',
					'export'  => $this->unique,
					'wpnonce' => $nonce
				), admin_url( 'admin-ajax.php' ) ) );

				$encoded_options = '';
				if ( $options ) {

					$encoded_options = $this->encode_string( $options );

				}

				echo esc_html('<textarea name="_nonce" class="exopite-sof__import"></textarea>');
				echo esc_html('<small class="exopite-sof-info--small">( ' . esc_attr__( 'copy-paste your backup string here', 'swellenterprise' ) . ' )</small>');
				echo esc_html('<a href="#" class="button button-primary exopite-sof-import-js" data-confirm="' . esc_attr__( 'Are you sure, you want to overwrite existing options?', 'swellenterprise' ) . '">' . esc_attr__( 'Import a Backup', 'swellenterprise' ) . '</a>');

				echo esc_html('<hr />');
				echo esc_html('<textarea name="_nonce" class="exopite-sof__export" readonly>' . esc_attr( $encoded_options ) . '</textarea>');
				echo esc_html('<a href="' . esc_attr( $export ) . '" class="button button-primary" target="_blank">' . esc_attr__( 'Download Backup', 'swellenterprise' ) . '</a>');

				echo esc_html('<hr />');
				echo esc_html('<small class="exopite-sof-info--small exopite-sof-info--warning">' . esc_attr__( 'Please be sure for reset all of framework options.', 'swellenterprise' ) . '</small>');
				echo esc_html('<a href="#" class="button button-warning exopite-sof-reset-js" data-confirm="' . esc_attr__( 'Are you sure, you want to reset all options?', 'swellenterprise' ) . '">' . esc_attr__( 'Reset All Options', 'swellenterprise' ) . '</a>');

				echo esc_html('<div class="exopite-sof--data" data-admin="' . admin_url( 'admin-ajax.php' ) . '" data-unique="' . esc_attr( $this->unique ) . '" data-wpnonce="' . esc_attr( $nonce ) . '"></div>');

			} else {

				echo esc_str( 'This item only available in menu!<br>' ); 

			}

			echo esc_attr( $this->element_after() );

		}

		/**
		 * Encode string for backup options
		 */
		function encode_string( $option ) {
			return json_encode( $option );
			// return rtrim( strtr( call_user_func( 'base' . '64' . '_encode', addslashes( gzcompress( serialize( $option ), 9 ) ) ), '+/', '-_' ), '=' );
		}

		/**
		 * Decode string for backup options
		 */
		function decode_string( $option ) {
			return json_decode( sanitize_text_field( $_POST['value'] ), true );
			// return unserialize( gzuncompress( stripslashes( call_user_func( 'base' . '64' . '_decode', rtrim( strtr( $option, '-_', '+/' ), '=' ) ) ) ) );
		}

		public static function enqueue( $args ) {

			/*
			 * https://sweetalert.js.org/guides/
			 */
			// wp_enqueue_script( 'sweetalert', '//unpkg.com/sweetalert/dist/sweetalert.min.js', false, '2.1.0', true );
			$resources = array(
				array(
					'name'       => 'sweetalert',
					'fn'         => 'sweetalert.min.js',
					'type'       => 'script',
					'dependency' => false,
					'version'    => '2.1.0',
					'attr'       => true,
				),
			);

			parent::do_enqueue( $resources, $args );

		}

	}

}
