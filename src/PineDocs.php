<?php

	class PineDocs {

		const version = '1.0.0-beta.4';

		static public $config;

		static public function load_config() {
			$config = yaml_parse_file('../config/config.yaml');

			if (!$config) {
				exit('Error: Could not parse config.yaml');
			}

			self::$config = (object) $config;

			// Make sure the 'content_dir' is ending on a '/' for security.
			self::set_content_dir();

			// Set defaults
			if (!isset(self::$config->title) || (empty(self::$config->title))) {
				self::$config->title = 'PineDocs';
			}

			// Make sure $theme is set.
			if (!isset(self::$config->theme) || (empty(self::$config->theme))) {
				self::$config->theme = 'default';
				// $theme = $config->theme ?? array('default');
				// if (!is_array($theme)) {
					// $theme = array($theme);
				// }
			}

			// Make sure $color_theme is set.
			if (!isset(self::$config->color_theme) || (empty(self::$config->color_theme))) {
				self::$config->color_theme = 'default';
			}

			if (!isset(self::$config->logo) || empty(self::$config->logo)) {
				// Use default logo.
				self::$config->logo = 'PineDocs.png';
			}

			if (!isset(self::$config->highlight_theme) || (empty(self::$config->highlight_theme))) {
				self::$config->highlight_theme = 'default';
			}

			if (!isset(self::$config->code_transparent_bg)) {
				self::$config->code_transparent_bg = false;
			}

			if (!isset(self::$config->open_dirs)) {
				self::$config->open_dirs = 0;
			}

			if (!isset(self::$config->show_file_extension)) {
				# Set default value.
				self::$config->show_file_extension = true;
			}
		}


		static public function exclude_file(string $full_path) : bool {
			if (isset(self::$config->exclude_files)) {
				foreach (self::$config->exclude_files as $value) {
					if (substr($value, 0, 1) == '/') {
						// Regex
						if (preg_match($value, $full_path)) {
							return true;
						}
					} else {
						// Not regex.
						if (stripos($full_path, $value) !== false) {
							return true;
						}
					}
				}
			}
			return false;
		}


		static private function set_content_dir() {
			if (empty(self::$config->content_dir)) {
				exit("Error: 'content_dir' must be specified in the config.yaml file");
			}

			self::$config->content_dir = xy_format_path(self::$config->content_dir, true);
		}


	}
