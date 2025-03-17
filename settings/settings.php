<?php

if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'sanitize_css_settings_soovex' ) ) {
    function sanitize_css_settings_soovex($input) {
		// Set safe defaults
		$output = [
			'minify' => 'yes',
			'user_roles' => ['administrator']
		];

		// Validate 'minify' setting
		if (isset($input['minify'])) {
			$output['minify'] = in_array($input['minify'], ['yes', 'no'], true) 
				? $input['minify'] 
				: 'yes';
		}

		// Validate user roles
	    if (!empty($input['user_roles']) && is_array($input['user_roles'])) {
	        $valid_roles = array_keys(wp_roles()->get_names());
	        $output['user_roles'] = array_intersect(
	            array_map('sanitize_key', $input['user_roles']),
	            $valid_roles
	        );

	        // Fallback to administrator if empty
	        if (empty($output['user_roles'])) {
	            $output['user_roles'] = ['administrator'];
	        }
	    }

		return $output;
	}
}


final class SOOVEX_Settings {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        // Add Custom CSS to Page Settings
        add_action('elementor/element/page-settings/section_page_style/after_section_end', [$this, '_soovex_add_page_css_control'], 10, 2);

        // Add Custom CSS to Sections, Columns, and Widgets
        add_action('elementor/element/after_section_end', [$this, '_soovex_add_element_css_control'], 10, 3);

        // Output CSS on the frontend
        add_action('wp_enqueue_scripts', [$this, '_soovex_output_css']);

        // Add body class for page-specific CSS
        add_filter('body_class', [$this, '_soovex_add_body_class']);

        // Add element class for scoping
        add_action('elementor/element/after_add_attributes', [$this, '_soovex_add_element_class']);

        // Register plugin settings
        // add_action('admin_init', [$this, '_soovex_register_settings'], 5);
        add_action('admin_init', [$this, 'soovex_register_settings'], 5);

        // Add settings tab in Elementor
        add_action('elementor/admin/after_create_settings/elementor', [$this, '_soovex_add_settings_tab']);
    }

    // Add Page CSS Control
    public function _soovex_add_page_css_control($element, $args) {
        if (!$this->_soovex_user_can_access()) {
            return;
        }

        $element->start_controls_section(
            '_soovex_page_css_section',
            [
                'label' => __('Soovex Custom CSS', 'soovex-custom-css-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            '_soovex_page_css',
            [
                'label' => __('Page CSS', 'soovex-custom-css-for-elementor'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'theme' => 'monokai',
                'description' => __('CSS added here will only apply to this page', 'soovex-custom-css-for-elementor'),
            ]
        );

        $element->end_controls_section();
    }

    // Add Element CSS Control
    public function _soovex_add_element_css_control($element, $section_id, $args) {
        if (!$this->_soovex_user_can_access()) {
            return;
        }

        // Add CSS control to Sections, Columns, and Widgets
        if ($section_id === 'section_advanced' || $section_id === 'section_custom_css_pro') {
            $element->start_controls_section(
                '_soovex_element_css_section',
                [
                    'label' => __('Soovex Custom CSS', 'soovex-custom-css-for-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                ]
            );

            $element->add_control(
                '_soovex_element_css',
                [
                    'label' => __('Element CSS', 'soovex-custom-css-for-elementor'),
                    'type' => \Elementor\Controls_Manager::CODE,
                    'language' => 'css',
                    'theme' => 'monokai',
                    'description' => __('CSS added here will only apply to this element, <b>SELECTOR</b> not working', 'soovex-custom-css-for-elementor'),
                ]
            );

            $element->end_controls_section();
        }
    }

    // Output CSS
    public function _soovex_output_css() {
        if (!is_singular()) return;

        try {
            $post_id = get_the_ID();
            $css = '';

            // Page CSS
            $page_css = get_post_meta($post_id, '_soovex_page_css', true);
            if ($page_css) {
                $css .= $this->_soovex_scope_css($page_css, 'page', $post_id);
            }

            // Element CSS
            $elements_css = $this->_soovex_get_elements_css($post_id);
            if ($elements_css) {
                $css .= $elements_css;
            }

            // Minify CSS
            $settings = get_option('soovex_settings_group');
            if (!empty($settings['minify'])) {
                $css = $this->_soovex_minify_css($css);
            }

            if (!empty($css)) {
                wp_add_inline_style('elementor-frontend', $css);
            }
        } catch (Exception $e) {
            $this->_soovex_handle_error($e);
        }
    }

    // Add Body Class for Page Scoping
    public function _soovex_add_body_class($classes) {
        if (is_singular()) {
            $classes[] = '_soovex-page-' . get_the_ID();
        }
        return $classes;
    }

    // Add Element Class for Scoping
    public function _soovex_add_element_class($element) {
        $settings = $element->get_settings();
        if (!empty($settings['_soovex_element_css'])) {
            $element->add_render_attribute('_wrapper', 'class', '_soovex-element-' . $element->get_id());
        }
    }




	public function soovex_register_settings() {
		register_setting(
			'soovex_settings_group',
			'soovex_settings_option',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'sanitize_css_settings_soovex',
			)
		);
    }

    // Add Settings Tab
    public function _soovex_add_settings_tab($settings) {
        $settings->add_tab('soovex_settings_group', [
            'label' => __('Soovex Custom CSS', 'soovex-custom-css-for-elementor'),
            'sections' => [
                '_soovex_general' => [
                    'label' => __('General Settings', 'soovex-custom-css-for-elementor'),
                    'fields' => [
                        'minify' => [
                            'label' => __('Minify CSS', 'soovex-custom-css-for-elementor'),
                            'field_args' => [
                                'type' => 'select',
                                'options' => [
                                    'yes' => __('Yes', 'soovex-custom-css-for-elementor'),
                                    'no' => __('No', 'soovex-custom-css-for-elementor'),
                                ],
                                'default' => 'yes'
                            ]
                        ],
                        'user_roles' => [
                            'label' => __('Allowed User Roles', 'soovex-custom-css-for-elementor'),
                            'field_args' => [
                                'type' => 'select',
                                'multiple' => true,
                                'options' => $this->_soovex_get_roles(),
                                'default' => ['administrator', 'editor']
                            ]
                        ],
                        'documentation_button' => [
                            'field_args' => [
                                'type' => 'raw_html',
                                'html' => '<button type="button" class="button button-primary" onclick="soovexOpenDocumentation()">📖 Open Documentation</button>',
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }


    // Helper: Get User Roles
    private function _soovex_get_roles() {
        global $wp_roles;
        return $wp_roles->get_names();
    }

    // Helper: Check User Access
    private function _soovex_user_can_access() {
        $settings = get_option('soovex_settings_group');
        $allowed_roles = !empty($settings['user_roles']) ? $settings['user_roles'] : ['administrator', 'editor'];
        return array_intersect(wp_get_current_user()->roles, $allowed_roles);
    }

    // Helper: Scope CSS
    private function _soovex_scope_css($css, $type, $id) {
        $scope_class = $type === 'page' ? 'body._soovex-page-' . $id : '._soovex-element-' . $id;
        $scoped_css = '';

        foreach (explode('}', $css) as $rule) {
            if (trim($rule) === '') continue;
            list($selector, $styles) = array_pad(explode('{', $rule, 2), 2, '');
            $scoped_css .= "$scope_class $selector { $styles } ";
        }

        return $scoped_css;
    }

    // Helper: Get Elements CSS
    private function _soovex_get_elements_css($post_id) {
        $document = \Elementor\Plugin::$instance->documents->get($post_id);
        if (!$document) return '';

        $css = '';
        \Elementor\Plugin::$instance->db->iterate_data($document->get_elements_data(), function($element) use (&$css) {
            if (!empty($element['settings']['_soovex_element_css'])) {
                $css .= $this->_soovex_scope_css(
                    $element['settings']['_soovex_element_css'],
                    'element',
                    $element['id']
                );
            }
        });

        return $css;
    }

    // Helper: Minify CSS
    private function _soovex_minify_css($css) {
        return preg_replace(['/\/\*.*?\*\//s', '/\s+/', '/\s*([:;{}])\s*/', '/;\s*/'], ['', ' ', '\1', ';'], $css);
    }

    // Error Handling
    private function _soovex_handle_error($e) {
        // WordPress-approved debug logging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if (function_exists('wp_log')) {
                wp_log('Soovex Custom CSS Error: ' . $e->getMessage());
            } else {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                error_log('Soovex Custom CSS Error: ' . $e->getMessage());
            }
        }
         
        if (current_user_can('manage_options')) {
            add_action('admin_notices', function() use ($e) {
                echo '<div class="notice notice-error"><p>' 
                    . esc_html($e->getMessage()) 
                    . '</p></div>';
            });
        }
    }

}



