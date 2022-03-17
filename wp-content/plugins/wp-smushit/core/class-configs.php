<?php
/**
 * Configs class.
 *
 * @since 3.8.5
 * @package Smush\Core
 */

namespace Smush\Core;

use WP_Smush;

/**
 * Class Configs
 *
 * @since 3.8.5
 */
class Configs {

	/**
	 * Name of the option holding the configs.
	 *
	 * @since 3.8.5
	 */
	const OPTION_NAME = 'preset_configs';

	/**
	 * List of pro features.
	 *
	 * @since 3.8.5
	 *
	 * @var array
	 */
	private $pro_features = array( 'lossy', 'original', 'backup', 'png_to_jpg', 's3', 'nextgen', 'cdn', 'auto_resize', 'webp', 'webp_mod' );

	/**
	 * Array of the settings and their labels.
	 * The setting's name is the key of the array, the label its value.
	 *
	 * @since 3.8.5
	 *
	 * @var array
	 */
	private $settings_labels;

	/**
	 * Gets the local list of configs via Smush's endpoint.
	 *
	 * @since 3.8.6
	 *
	 * @return bool
	 */
	public function get_callback() {
		$stored_configs = get_site_option( 'wp-smush-' . self::OPTION_NAME, false );

		if ( false === $stored_configs ) {
			$stored_configs = array( $this->get_basic_config() );
			update_site_option( 'wp-smush-' . self::OPTION_NAME, $stored_configs );
		}
		return $stored_configs;
	}

	/**
	 * Updates the local list of configs via Smush's endpoint.
	 *
	 * @since 3.8.6
	 *
	 * @param \WP_REST_Request $request Class containing the request data.
	 * @return bool
	 */
	public function post_callback( $request ) {
		$data = json_decode( $request->get_body(), true );
		if ( ! is_array( $data ) ) {
			return new \WP_Error( '400', esc_html__( 'Missing configs data', 'wp-smushit' ), array( 'status' => 400 ) );
		}

		// Do we really need to re-sanitize here?
		$sanitized_data = $this->sanitize_configs_list( $data );
		update_site_option( 'wp-smush-' . self::OPTION_NAME, $sanitized_data );

		return $data;
	}

	/**
	 * Checks whether the current user can perform requests to Smush's endpoint.
	 *
	 * @since 3.8.6
	 *
	 * @return bool
	 */
	public function permission_callback() {
		$capability = is_multisite() ? 'manage_network' : 'manage_options';
		return current_user_can( $capability );
	}

	/**
	 * Adds the basic configuration to the local configs.
	 *
	 * @since 3.8.6
	 */
	private function get_basic_config() {
		$basic_config = array(
			'id'          => 1,
			'name'        => __( 'Basic config', 'wp-smushit' ),
			'description' => __( 'Recommended performance config for every site.', 'wp-smushit' ),
			'default'     => true,
			'config'      => array(
				'configs' => array(
					'settings' => array(
						'auto'              => true,
						'lossy'             => true,
						'strip_exif'        => true,
						'resize'            => false,
						'detection'         => false,
						'original'          => true,
						'backup'            => true,
						'png_to_jpg'        => true,
						'nextgen'           => false,
						's3'                => false,
						'gutenberg'         => false,
						'js_builder'        => false,
						'cdn'               => false,
						'auto_resize'       => false,
						'webp'              => true,
						'usage'             => false,
						'accessible_colors' => false,
						'keep_data'         => true,
						'lazy_load'         => false,
						'background_images' => true,
						'rest_api_support'  => false,
						'webp_mod'          => false,
					),
				),
			),
		);

		$basic_config['config']['strings'] = $this->format_config_to_display( $basic_config['config']['configs'] );

		return $basic_config;
	}

	/**
	 * Sanitizes the full list of configs.
	 *
	 * @since 3.8.6
	 *
	 * @param array $configs_list Configs list to sanitize.
	 * @return array
	 */
	private function sanitize_configs_list( $configs_list ) {
		$sanitized_list = array();

		foreach ( $configs_list as $config_data ) {
			$sanitized_data = array(
				'id'          => filter_var( $config_data['id'], FILTER_VALIDATE_INT ),
				'name'        => filter_var( $config_data['name'], FILTER_SANITIZE_STRING ),
				'description' => filter_var( $config_data['description'], FILTER_SANITIZE_STRING ),
				'config'      => array(
					'configs' => $this->sanitize_config( $config_data['config']['configs'] ),
					'strings' => filter_var( $config_data['config']['strings'], FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY ),
				),
			);

			if ( ! empty( $config_data['hub_id'] ) ) {
				$sanitized_data['hub_id'] = filter_var( $config_data['hub_id'], FILTER_VALIDATE_INT );
			}
			if ( isset( $config_data['default'] ) ) {
				$sanitized_data['default'] = filter_var( $config_data['default'], FILTER_VALIDATE_BOOLEAN );
			}

			$sanitized_list[] = $sanitized_data;
		}

		return $sanitized_list;
	}

	/**
	 * Tries to save the uploaded config.
	 *
	 * @since 3.8.5
	 *
	 * @param array $file The uploaded file.
	 *
	 * @return true|string
	 */
	public function save_uploaded_config( $file ) {
		try {
			return $this->decode_and_validate_config_file( $file );
		} catch ( \Exception $e ) {
			return new \WP_Error( 'error_saving', $e->getMessage() );
		}
	}

	/**
	 * Tries to decode and validate the uploaded config file.
	 *
	 * @since 3.8.5
	 *
	 * @param array $file The uploaded file.
	 *
	 * @return array
	 *
	 * @throws \Exception When there's an error with the uploaded file.
	 */
	private function decode_and_validate_config_file( $file ) {
		if ( ! $file ) {
			throw new \Exception( __( 'The configs file is required', 'wp-smushit' ) );
		} elseif ( ! empty( $file['error'] ) ) {
			/* translators: error message */
			throw new \Exception( sprintf( __( 'Error: %s.', 'wp-smushit' ), $file['error'] ) );
		} elseif ( 'application/json' !== $file['type'] ) {
			throw new \Exception( __( 'The file must be a JSON.', 'wp-smushit' ) );
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$json_file = file_get_contents( $file['tmp_name'] );
		if ( ! $json_file ) {
			throw new \Exception( __( 'There was an error getting the contents of the file.', 'wp-smushit' ) );
		}

		$configs = json_decode( $json_file, true );
		if ( empty( $configs ) || ! is_array( $configs ) ) {
			throw new \Exception( __( 'There was an error decoding the file.', 'wp-smushit' ) );
		}

		// Make sure the config has a name and configs.
		if ( empty( $configs['name'] ) || empty( $configs['config'] ) ) {
			throw new \Exception( __( 'The uploaded config must have a name and a set of settings. Please make sure the uploaded file is the correct one.', 'wp-smushit' ) );
		}

		// Sanitize.
		$configs['config'] = array(
			'configs' => $this->sanitize_config( $configs['config']['configs'] ),
			// Let's re-create this to avoid differences between imported settings coming from other versions.
			'strings' => $this->format_config_to_display( $configs['config']['configs'] ),
		);

		if ( empty( $configs['config']['configs'] ) ) {
			throw new \Exception( __( 'The provided configs list isn’t correct. Please make sure the uploaded file is the correct one.', 'wp-smushit' ) );
		}

		// Don't keep these if they exist.
		unset( $configs['hub_id'] );
		unset( $configs['default'] );

		return $configs;
	}

	/**
	 * Applies a config given its ID.
	 *
	 * @since 3.8.6
	 *
	 * @param string $id The ID of the config to apply.
	 */
	public function apply_config_by_id( $id ) {
		$stored_configs = get_site_option( 'wp-smush-' . self::OPTION_NAME );

		$config = false;
		foreach ( $stored_configs as $config_data ) {
			if ( strval( $config_data['id'] ) === $id ) {
				$config = $config_data;
				break;
			}
		}

		// The config with the given ID doesn't exist.
		if ( ! $config ) {
			return new \WP_Error( '404', __( 'The given config ID does not exist', 'wp-smushit' ) );
		}

		$this->apply_config( $config['config']['configs'] );
	}

	/**
	 * Applies the given config.
	 *
	 * @since 3.8.6
	 *
	 * @param array $config The config to apply.
	 */
	public function apply_config( $config ) {

		$sanitized_config = $this->sanitize_config( $config );

		// Update 'networkwide' options in multisites.
		if ( is_multisite() && isset( $sanitized_config['networkwide'] ) ) {
			update_site_option( 'wp-smush-networkwide', $sanitized_config['networkwide'] );
		}

		$settings_handler = Settings::get_instance();

		// Update image sizes.
		if ( isset( $sanitized_config['resize_sizes'] ) ) {
			$settings_handler->set_setting( 'wp-smush-resize_sizes', $sanitized_config['resize_sizes'] );
		}

		// Update settings. We could reuse the `save` method from settings to handle this instead.
		if ( ! empty( $sanitized_config['settings'] ) ) {
			$stored_settings = $settings_handler->get_setting( 'wp-smush-settings' );

			// Keep the keys that are in use in this version.
			$new_settings = array_intersect_key( $sanitized_config['settings'], $stored_settings );

			if ( $new_settings ) {
				if ( ! WP_Smush::is_pro() ) {
					// Disable the pro features before applying them.
					foreach ( $this->pro_features as $name ) {
						$new_settings[ $name ] = false;
					}
				}

				// Update the flag file when local webp changes.
				if ( isset( $new_settings['webp_mod'] ) && $new_settings['webp_mod'] !== $stored_settings['webp_mod'] ) {
					WP_Smush::get_instance()->core()->mod->webp->toggle_webp( $new_settings['webp_mod'] );
				}

				// Update the CDN status for CDN changes.
				if ( isset( $new_settings['cdn'] ) && $new_settings['cdn'] !== $stored_settings['cdn'] ) {
					WP_Smush::get_instance()->core()->mod->cdn->toggle_cdn( $new_settings['cdn'] );
				}

				// Keep the stored settings that aren't present in the incoming one.
				$new_settings = array_merge( $stored_settings, $new_settings );
				$settings_handler->set_setting( 'wp-smush-settings', $new_settings );
			}
		}

		// Update lazy load.
		if ( ! empty( $sanitized_config['lazy_load'] ) ) {
			$stored_lazy_load = $settings_handler->get_setting( 'wp-smush-lazy_load' );

			// Save the defaults before applying the config if the current settings aren't set.
			if ( empty( $stored_lazy_load ) ) {
				$settings_handler->init_lazy_load_defaults();
				$stored_lazy_load = $settings_handler->get_setting( 'wp-smush-lazy_load' );
			}

			// Keep the settings that are in use in this version.
			foreach ( $sanitized_config['lazy_load'] as $key => $value ) {
				if ( is_array( $value ) && is_array( $stored_lazy_load[ $key ] ) ) {
					$sanitized_config['lazy_load'][ $key ] = array_intersect_key( $value, $stored_lazy_load[ $key ] );
				}
			}

			// Keep the stored settings that aren't present in the incoming one.
			$new_lazy_load = array_replace_recursive( $stored_lazy_load, $sanitized_config['lazy_load'] );
			$settings_handler->set_setting( 'wp-smush-lazy_load', $new_lazy_load );
		}
	}

	/**
	 * Gets a new config array based on the current settings.
	 *
	 * @since 3.8.5
	 *
	 * @return array
	 */
	public function get_config_from_current() {
		$settings = Settings::get_instance();

		$stored_settings = $settings->get_setting( 'wp-smush-settings' );

		$configs = array( 'settings' => $stored_settings );

		if ( $stored_settings['resize'] ) {
			$configs['resize_sizes'] = $settings->get_setting( 'wp-smush-resize_sizes' );
		}

		// Let's store this only for multisites.
		if ( is_multisite() ) {
			$configs['networkwide'] = get_site_option( 'wp-smush-networkwide' );
		}

		// There's a site_option that handles this.
		unset( $configs['settings']['networkwide'] );

		// Looks like unused.
		unset( $configs['settings']['api_auth'] );

		// These are unique per site. They shouldn't be used.
		unset( $configs['settings']['bulk'] );

		// Include the lazy load settings only when lazy load is enabled.
		if ( ! empty( $configs['settings']['lazy_load'] ) ) {
			$lazy_load_settings = $settings->get_setting( 'wp-smush-lazy_load' );

			if ( ! empty( $lazy_load_settings ) ) {
				// Exclude unique settings.
				unset( $lazy_load_settings['animation']['placeholder'] );
				unset( $lazy_load_settings['animation']['spinner'] );
				unset( $lazy_load_settings['exclude-pages'] );
				unset( $lazy_load_settings['exclude-classes'] );

				if ( 'fadein' !== $lazy_load_settings['animation']['selected'] ) {
					unset( $lazy_load_settings['animation']['fadein'] );
				}

				$configs['lazy_load'] = $lazy_load_settings;
			}
		}

		// Exclude CDN fields if CDN is disabled.
		if ( empty( $configs['settings']['cdn'] ) ) {
			foreach ( $settings->get_cdn_fields() as $field ) {
				if ( 'cdn' !== $field ) {
					unset( $configs['settings'][ $field ] );
				}
			}
		}

		return array(
			'config' => array(
				'configs' => $configs,
				'strings' => $this->format_config_to_display( $configs ),
			),
		);
	}

	/**
	 * Sanitizes the given config.
	 *
	 * @since 3.8.5
	 *
	 * @param array $config Config array to sanitize.
	 *
	 * @return array
	 */
	private function sanitize_config( $config ) {
		$sanitized = array();

		if ( isset( $config['networkwide'] ) ) {
			if ( ! is_array( $config['networkwide'] ) ) {
				$sanitized['networkwide'] = $config['networkwide'];
			} else {
				$sanitized['networkwide'] = filter_var( $config['networkwide'], FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
			}
		}

		if ( ! empty( $config['settings'] ) ) {
			$sanitized['settings'] = filter_var( $config['settings'], FILTER_VALIDATE_BOOLEAN, FILTER_REQUIRE_ARRAY );
		}

		if ( isset( $config['resize_sizes'] ) ) {
			if ( is_bool( $config['resize_sizes'] ) ) {
				$sanitized['resize_sizes'] = $config['resize_sizes'];
			} else {
				$sanitized['resize_sizes'] = array(
					'width'  => (int) $config['resize_sizes']['width'],
					'height' => (int) $config['resize_sizes']['height'],
				);
			}
		}

		if ( ! empty( $config['lazy_load'] ) ) {
			$args = array(
				'format'          => array(
					'filter' => FILTER_VALIDATE_BOOLEAN,
					'flags'  => FILTER_REQUIRE_ARRAY + FILTER_NULL_ON_FAILURE,
				),
				'output'          => array(
					'filter' => FILTER_VALIDATE_BOOLEAN,
					'flags'  => FILTER_REQUIRE_ARRAY,
				),
				'animation'       => array(
					'filter' => FILTER_SANITIZE_STRING,
					'flags'  => FILTER_REQUIRE_ARRAY,
				),
				'include'         => array(
					'filter' => FILTER_VALIDATE_BOOLEAN,
					'flags'  => FILTER_REQUIRE_ARRAY,
				),
				'exclude-classes' => array(
					'filter' => FILTER_SANITIZE_STRING,
					'flags'  => FILTER_REQUIRE_ARRAY,
				),
				'footer'          => FILTER_VALIDATE_BOOLEAN,
				'native'          => FILTER_VALIDATE_BOOLEAN,
				'noscript'        => FILTER_VALIDATE_BOOLEAN,
			);

			$sanitized['lazy_load'] = filter_var_array( $config['lazy_load'], $args, false );
		}

		return $sanitized;
	}

	/**
	 * Formatting methods.
	 */

	/**
	 * Formats the given config to be displayed.
	 * Used when displaying the list of configs and when sending a config to the Hub.
	 *
	 * @since 3.8.5
	 *
	 * @param array $config The config to format.
	 *
	 * @return array Contains an array for each setting. Each with a 'label' and 'value' keys.
	 */
	private function format_config_to_display( $config ) {
		$settings_data = array(
			'bulk_smush'   => Settings::get_instance()->get_bulk_fields(),
			'lazy_load'    => Settings::get_instance()->get_lazy_load_fields(),
			'cdn'          => Settings::get_instance()->get_cdn_fields(),
			'webp_mod'     => array(),
			'integrations' => Settings::get_instance()->get_integrations_fields(),
			'tools'        => Settings::get_instance()->get_tools_fields(),
			'settings'     => Settings::get_instance()->get_settings_fields(),
		);

		$display_array = array();

		if ( ! empty( $config['settings'] ) ) {
			foreach ( $settings_data as $name => $fields ) {

				// Display the setting inactive when the module is off.
				if (
					'webp_mod' === $name ||
					( in_array( $name, array( 'cdn', 'lazy_load' ), true ) && empty( $config['settings'][ $name ] ) )
				) {
					$display_array[ $name ] = $this->format_boolean_setting_value( $name, $config['settings'][ $name ] );
					continue;
				}

				$display_array[ $name ] = $this->get_settings_display_value( $config, $fields );
			}

			// Append the resize_sizes to the Bulk Smush display settings.
			if ( ! empty( $config['settings']['resize'] ) && ! empty( $config['resize_sizes'] ) ) {
				$display_array['bulk_smush'][] = sprintf(
					/* translators: 1. Resize-size max width, 2. Resize-size max height */
					__( 'Full images max-sizes to resize - Max-width: %1$s. Max height: %2$s', 'wp-smushit' ),
					$config['resize_sizes']['width'],
					$config['resize_sizes']['height']
				);
			}

			// Append the lazy laod details to the the Lazy Load display settings.
			if ( ! empty( $config['settings']['lazy_load'] ) && ! empty( $config['lazy_load'] ) ) {
				$display_array['lazy_load'] = array_merge( $display_array['lazy_load'], $this->get_lazy_load_settings_to_display( $config ) );
			}
		}

		// Display only for multisites, if the setting exists.
		if ( is_multisite() && isset( $config['networkwide'] ) ) {
			$display_array['networkwide'] = $this->get_networkwide_settings_to_display( $config );
		}

		// Format the values to what's expected in front. A string within an array.
		array_walk(
			$display_array,
			function( &$value ) {
				if ( ! is_string( $value ) ) {
					$value = implode( PHP_EOL, $value );
				}
				$value = array( $value );
			}
		);

		return $display_array;
	}

	/**
	 * Formats the given fields that belong to the "settings" option.
	 *
	 * @since 3.8.5
	 *
	 * @param array $config The config to format.
	 * @param array $fields The fields to look for.
	 *
	 * @return array
	 */
	private function get_settings_display_value( $config, $fields ) {
		$formatted_rows = array();

		$extra_labels = array(
			's3'        => __( 'Amazon S3', 'wp-smushit' ),
			'nextgen'   => __( 'NextGen Gallery', 'wp-smushit' ),
			'lazy_load' => __( 'Lazy Load', 'wp-smushit' ),
			'cdn'       => __( 'CDN', 'wp-smushit' ),
			'keep_data' => __( 'Keep Data On Uninstall', 'wp-smushit' ),
		);

		foreach ( $fields as $name ) {
			if ( isset( $config['settings'][ $name ] ) ) {
				$label = Settings::get_instance()->get_setting_data( $name, 'short-label' );

				if ( empty( $label ) ) {
					$label = ! empty( $extra_labels[ $name ] ) ? $extra_labels[ $name ] : $name;
				}

				$formatted_rows[] = $label . ' - ' . $this->format_boolean_setting_value( $name, $config['settings'][ $name ] );
			}
		}
		return $formatted_rows;
	}

	/**
	 * Formats the boolean settings that are either 'active' or 'inactive'.
	 * If the setting belongs to a pro feature and
	 * this isn't a pro install, we display it as 'inactive'.
	 *
	 * @since 3.8.5
	 *
	 * @param string  $name The setting's name.
	 * @param boolean $value The setting's value.
	 * @return string
	 */
	private function format_boolean_setting_value( $name, $value ) {
		// Display the pro features as 'inactive' for free installs.
		if ( ! WP_Smush::is_pro() && in_array( $name, $this->pro_features, true ) ) {
			$value = false;
		}
		return $value ? __( 'Active', 'wp-smushit' ) : __( 'Inactive', 'wp-smushit' );
	}

	/**
	 * Formats the given lazy_load settings to be displayed.
	 *
	 * @since 3.8.5
	 *
	 * @param array $config The config to format.
	 *
	 * @return string
	 */
	private function get_lazy_load_settings_to_display( $config ) {
		$formatted_rows = array();

		// List of the available lazy load settings for this version and their labels.
		$settings_labels = array(
			'format'    => __( 'Media Types', 'wp-smushit' ),
			'output'    => __( 'Output Locations', 'wp-smushit' ),
			'include'   => __( 'Included Post Types', 'wp-smushit' ),
			'animation' => __( 'Display And Animation', 'wp-smushit' ),
			'footer'    => __( 'Load Scripts In Footer', 'wp-smushit' ),
			'native'    => __( 'Native Lazy Load Enabled', 'wp-smushit' ),
			'noscript'  => __( 'Disable Noscript', 'wp-smushit' ),
		);

		foreach ( $config['lazy_load'] as $key => $value ) {
			// Skip if the setting doesn't exist.
			if ( ! isset( $settings_labels[ $key ] ) ) {
				continue;
			}

			$formatted_value = $settings_labels[ $key ] . ' - ';

			if ( 'animation' === $key ) {
				// The special kid.
				$formatted_value .= __( 'Selected: ', 'wp-smushit' ) . $value['selected'];
				if ( ! empty( $value['fadein'] ) ) {
					$formatted_value .= __( '. Fade in duration: ', 'wp-smushit' ) . $value['fadein']['duration'];
					$formatted_value .= __( '. Fade in delay: ', 'wp-smushit' ) . $value['fadein']['delay'];
				}
			} elseif ( in_array( $key, array( 'footer', 'native', 'noscript' ), true ) ) {
				// Enabled/disabled settings.
				$formatted_value .= ! empty( $value ) ? __( 'Yes', 'wp-smushit' ) : __( 'No', 'wp-smushit' );

			} else {
				// Arrays.
				if ( in_array( $key, array( 'format', 'output', 'include' ), true ) ) {
					$value = array_keys( array_filter( $value ) );
				}

				if ( ! empty( $value ) ) {
					$formatted_value .= implode( ', ', $value );
				} else {
					$formatted_value .= __( 'none', 'wp-smushit' );
				}
			}

			$formatted_rows[] = $formatted_value;
		}

		return $formatted_rows;
	}

	/**
	 * Formats the 'networkwide' setting to display.
	 *
	 * @since 3.8.5
	 *
	 * @param array $config The config to format.
	 *
	 * @return string
	 */
	private function get_networkwide_settings_to_display( $config ) {
		if ( is_array( $config['networkwide'] ) ) {
			return implode( ', ', $config['networkwide'] );
		}
		return '1' === (string) $config['networkwide'] ? __( 'All', 'wp-smushit' ) : __( 'None', 'wp-smushit' );
	}
}
