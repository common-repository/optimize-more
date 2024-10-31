<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class OpmPluginSettings {
    
    private $settings;

    function __construct() {
        $this->init_settings();
        add_action('init', array($this, 'init'));
        add_action('opm_after_body', array($this, 'add_import_html'));
    }

    public function init() {
        
        // check or initiate import
        $this->import();

        if (!isset($_GET['opm-action'])) {
            return;
        }

        // check or initiate reset
        $this->reset_plugin();

        // check or initiate export
        $this->export();

    }

    public function get($key = "", $default = false) {
        
        $value = isset($this->settings[$key]) ? opm_removeslashes($this->settings[$key]) : $default;
    
        if (empty($value) || is_null($value)) {
            return false;
        }
    
        if (is_array($value) && count($value) == 0) {
            return false;
        }
    
        return $value;
    }

    public function reset() {
        $this->settings = array();
    }

    public function setAll($value) {
        $this->settings = $value;
    }

    public function getAll() {
        return $this->settings;
    }

    public function set($key, $value) {
        $this->settings[$key] = $value;
    }

    public function remove($key) {
        if (isset($this->settings[$key])) {
            unset($this->settings[$key]);
        }
    }

    public function save() {
        update_option("opm_options", $this->settings);
    }

    public function store() {
        do_action('opm_before_saving', $this);
        $this->reset();
        $this->set('version', OPTIMIZEMORE_VERSION);

        foreach ($this->keys() as $key) {
            $setting_value = '';
            if (isset($_POST[$key])) {
                $setting_value = opm_kses($_POST[$key]);
            }
            $this->set($key, $setting_value);
        }

        $placeholder = '';
        do_action('opm_save_addtional_settings', $this, $placeholder);

        $this->save();

        do_action('opm_after_saving', $this);

        opm_queue('Settings saved.');
        wp_redirect(opm_instance()->admin_url());
        exit;
    }

    public function init_settings() {
        $settings = get_option("opm_options", false);

        if (!$settings) {
            $settings = $this->default_options();
        }
	
    	$settings = is_array($settings) ? $settings : array("settings" => $settings);

        $this->settings = $settings;
		
    }

    public function add_import_html() {
        opm_instance()->admin_view('parts/import');
    }

    public function import() {
        
        if (!isset($_POST['opm_settings_nonce'])) return;

        if (!is_admin() && !current_user_can('manage_options')) {
            return;
        }

        if (!isset($_POST['opm-settings']) && !isset($_FILES['import_file'])) {
            return;
        }

        if (!isset($_FILES['import_file'])) {
            return;
        }

        if ($_FILES['import_file']['size'] == 0 && $_FILES['import_file']['name'] == '') {
            return;
        }

        // check nonce
        if (!wp_verify_nonce($_POST['opm_settings_nonce'], 'opm-settings-action')) {

           opm_queue('Sorry, your nonce did not verify.', 'error');
            wp_redirect(opm_instance()->admin_url());
            exit;
        }

        $import_field = 'import_file';
        $temp_file_raw = $_FILES[$import_field]['tmp_name'];
        $temp_file = esc_attr($temp_file_raw);
        $arr_file_type = $_FILES[$import_field];
        $uploaded_file_type = $arr_file_type['type'];
        $allowed_file_types = array('application/json');


        if (!in_array($uploaded_file_type, $allowed_file_types)) {
            opm_queue('Upload a valid .json file.', 'error');
            wp_redirect(opm_instance()->admin_url());
            exit;
        }

        $settings = (array)json_decode(
            file_get_contents($temp_file),
            true
        );

        unlink($temp_file);

        if (!$settings) {

            opm_queue('Nothing to import, please check your json file format.', 'error');
            wp_redirect(opm_instance()->admin_url());
            exit;

        }

        $this->setAll($settings);
        $this->save();

        opm_queue('Your Import has been completed.');

        wp_redirect(opm_instance()->admin_url());
        exit;
    }


    public function export() {
        if (!isset($_GET['opm-action']) || (isset($_GET['opm-action']) && $_GET['opm-action'] != 'export')) {
            return;
        }

        $settings = $this->getAll();

        if (!is_array($settings)) {
            $settings = array();
        }

        $settings = json_encode($settings);
		
		$site_name = get_bloginfo('url');
		$site_name = preg_replace('#^https?://#i', '', $site_name);

        header('Content-disposition: attachment; filename='.OPTIMIZEMORE_SLUG.'_'.$site_name.'-settings_' . date_i18n( 'd-m-Y' ) . '.json');
        header('Content-type: application/json');
        print $settings;
        exit;
    }

    public function reset_plugin() {
        global $wpdb;

        if ($_GET['opm-action'] != 'reset') {
            return;
        }

        delete_option("opm_options");
        $wpdb->get_results($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s", 'opm_o_%'));

        opm_queue('Settings reset.');
        wp_redirect(opm_instance()->admin_url());
        exit;
    }

    public function keys() {
        return array_keys($this->default_options());
    }

    public function get_default_option($key) {
        $settings = $this->default_options();
        return isset($settings[$key]) ? $settings[$key] : null;
    }

    public function default_options() {

        $settings = array(
	        
			'opm_developer_branding' => '',
	        
	        'opm_custom_pages_id' => '',
	        
			// async css
			'opm_async_css' => false,
			'opm_async_css_front_page' => false,
			'opm_async_css_pages' => false,
			'opm_async_css_archives' => false,
			'opm_async_css_singular' => false,
			'opm_async_css_wc_product' => false,
			'opm_async_css_wc_archives' => false,
			'opm_async_css_front_page_list' => '',
			'opm_async_css_pages_list' => '',
			'opm_async_css_archives_list' => '',
			'opm_async_css_singular_list' => '',
			'opm_async_css_wc_product_list' => '',
			'opm_async_css_wc_archives_list' => '',
			// defer js
			'opm_defer_js' => false,
			'opm_defer_js_front_page' => false,
			'opm_defer_js_pages' => false,
			'opm_defer_js_archives' => false,
			'opm_defer_js_singular' => false,
			'opm_defer_js_wc_product' => false,
			'opm_defer_js_wc_archives' => false,
			'opm_defer_js_front_page_list' => '',
			'opm_defer_js_pages_list' => '',
			'opm_defer_js_archives_list' => '',
			'opm_defer_js_singular_list' => '',
			'opm_defer_js_wc_product_list' => '',
			'opm_defer_js_wc_archives_list' => '',
			// advanced defer js
			'opm_adv_defer_js' => false,
			'opm_adv_defer_js_front_page' => false,
			'opm_adv_defer_js_pages' => false,
			'opm_adv_defer_js_archives' => false,
			'opm_adv_defer_js_singular' => false,
			'opm_adv_defer_js_wc_product' => false,
			'opm_adv_defer_js_wc_archives' => false,
			'opm_adv_defer_js_front_page_list' => '',
			'opm_adv_defer_js_pages_list' => '',
			'opm_adv_defer_js_archives_list' => '',
			'opm_adv_defer_js_singular_list' => '',
			'opm_adv_defer_js_wc_product_list' => '',
			'opm_adv_defer_js_wc_archives_list' => '',
			// delay css on interaction
			'opm_delay_css' => false,
			'opm_delay_css_front_page' => false,
			'opm_delay_css_pages' => false,
			'opm_delay_css_archives' => false,
			'opm_delay_css_singular' => false,
			'opm_delay_css_wc_product' => false,
			'opm_delay_css_wc_archives' => false,
			'opm_delay_css_front_page_list' => '',
			'opm_delay_css_pages_list' => '',
			'opm_delay_css_archives_list' => '',
			'opm_delay_css_singular_list' => '',
			'opm_delay_css_wc_product_list' => '',
			'opm_delay_css_wc_archives_list' => '',
			// delay js on interaction
			'opm_delay_js' => false,
			'opm_delay_js_front_page' => false,
			'opm_delay_js_pages' => false,
			'opm_delay_js_archives' => false,
			'opm_delay_js_singular' => false,
			'opm_delay_js_wc_product' => false,
			'opm_delay_js_wc_archives' => false,
			'opm_delay_js_front_page_list' => '',
			'opm_delay_js_pages_list' => '',
			'opm_delay_js_archives_list' => '',
			'opm_delay_js_singular_list' => '',
			'opm_delay_js_wc_product_list' => '',
			'opm_delay_js_wc_archives_list' => '',
			// preload css
			'opm_preload_css' => false,
			'opm_preload_css_front_page' => false,
			'opm_preload_css_pages' => false,
			'opm_preload_css_archives' => false,
			'opm_preload_css_singular' => false,
			'opm_preload_css_wc_product' => false,
			'opm_preload_css_wc_archives' => false,
			'opm_preload_css_front_page_list' => '',
			'opm_preload_css_pages_list' => '',
			'opm_preload_css_archives_list' => '',
			'opm_preload_css_singular_list' => '',
			'opm_preload_css_wc_product_list' => '',
			'opm_preload_css_wc_archives_list' => '',
			// preload js
			'opm_preload_js' => false,
			'opm_preload_js_front_page' => false,
			'opm_preload_js_pages' => false,
			'opm_preload_js_archives' => false,
			'opm_preload_js_singular' => false,
			'opm_preload_js_wc_product' => false,
			'opm_preload_js_wc_archives' => false,
			'opm_preload_js_front_page_list' => '',
			'opm_preload_js_pages_list' => '',
			'opm_preload_js_archives_list' => '',
			'opm_preload_js_singular_list' => '',
			'opm_preload_js_wc_product_list' => '',
			'opm_preload_js_wc_archives_list' => '',
			// preload fonts
			'opm_preload_font' => false,
			'opm_preload_font_front_page' => false,
			'opm_preload_font_pages' => false,
			'opm_preload_font_archives' => false,
			'opm_preload_font_singular' => false,
			'opm_preload_font_wc_product' => false,
			'opm_preload_font_wc_archives' => false,
			'opm_preload_font_front_page_list' => '',
			'opm_preload_font_pages_list' => '',
			'opm_preload_font_archives_list' => '',
			'opm_preload_font_singular_list' => '',
			'opm_preload_font_wc_product_list' => '',
			'opm_preload_font_wc_archives_list' => '',
			// preload imgs
			'opm_preload_img' => false,
			'opm_preload_img_front_page' => false,
			'opm_preload_img_pages' => false,
			'opm_preload_img_archives' => false,
			'opm_preload_img_singular' => false,
			'opm_preload_img_wc_product' => false,
			'opm_preload_img_wc_archives' => false,
			'opm_preload_img_front_page_list' => '',
			'opm_preload_img_pages_list' => '',
			'opm_preload_img_archives_list' => '',
			'opm_preload_img_singular_list' => '',
			'opm_preload_img_wc_product_list' => '',
			'opm_preload_img_wc_archives_list' => '',
			// remove css
			'opm_remove_css' => false,
			'opm_remove_css_front_page' => false,
			'opm_remove_css_pages' => false,
			'opm_remove_css_archives' => false,
			'opm_remove_css_singular' => false,
			'opm_remove_css_wc_product' => false,
			'opm_remove_css_wc_archives' => false,
			'opm_remove_css_front_page_list' => '',
			'opm_remove_css_pages_list' => '',
			'opm_remove_css_archives_list' => '',
			'opm_remove_css_singular_list' => '',
			'opm_remove_css_wc_product_list' => '',
			'opm_remove_css_wc_archives_list' => '',
			// remove js
			'opm_remove_js' => false,
			'opm_remove_js_front_page' => false,
			'opm_remove_js_pages' => false,
			'opm_remove_js_archives' => false,
			'opm_remove_js_singular' => false,
			'opm_remove_js_wc_product' => false,
			'opm_remove_js_wc_archives' => false,
			'opm_remove_js_front_page_list' => '',
			'opm_remove_js_pages_list' => '',
			'opm_remove_js_archives_list' => '',
			'opm_remove_js_singular_list' => '',
			'opm_remove_js_wc_product_list' => '',
			'opm_remove_js_wc_archives_list' => '',
			// inline css
			'opm_inline_css' => false,
			'opm_inline_css_front_page' => false,
			'opm_inline_css_pages' => false,
			'opm_inline_css_archives' => false,
			'opm_inline_css_singular' => false,
			'opm_inline_css_wc_product' => false,
			'opm_inline_css_wc_archives' => false,
			'opm_inline_css_front_page_list' => '',
			'opm_inline_css_pages_list' => '',
			'opm_inline_css_archives_list' => '',
			'opm_inline_css_singular_list' => '',
			'opm_inline_css_wc_product_list' => '',
			'opm_inline_css_wc_archives_list' => '',
			// inline js
			'opm_inline_js' => false,
			'opm_inline_js_front_page' => false,
			'opm_inline_js_pages' => false,
			'opm_inline_js_archives' => false,
			'opm_inline_js_singular' => false,
			'opm_inline_js_wc_product' => false,
			'opm_inline_js_wc_archives' => false,
			'opm_inline_js_front_page_list' => '',
			'opm_inline_js_pages_list' => '',
			'opm_inline_js_archives_list' => '',
			'opm_inline_js_singular_list' => '',
			'opm_inline_js_wc_product_list' => '',
			'opm_inline_js_wc_archives_list' => '',
			// delay content
			'opm_delay_content' => false,
			'opm_delay_content_front_page' => false,
			'opm_delay_content_pages' => false,
			'opm_delay_content_archives' => false,
			'opm_delay_content_singular' => false,
			'opm_delay_content_wc_product' => false,
			'opm_delay_content_wc_archives' => false,
			'opm_delay_content_front_page_list' => '',
			'opm_delay_content_pages_list' => '',
			'opm_delay_content_archives_list' => '',
			'opm_delay_content_singular_list' => '',
			'opm_delay_content_wc_product_list' => '',
			'opm_delay_content_wc_archives_list' => '',
			// extra css: critical
			'opm_use_critical_css_front_page' => false,
			'opm_use_critical_css_pages' => false,
			'opm_use_critical_css_archives' => false,
			'opm_use_critical_css_singular' => false,
			'opm_use_critical_css_wc_product' => false,
			'opm_use_critical_css_wc_archives' => false,
			// extra css: purified
			'opm_use_purified_css_front_page' => false,
			'opm_use_purified_css_pages' => false,
			'opm_use_purified_css_archives' => false,
			'opm_use_purified_css_singular' => false,
			'opm_use_purified_css_wc_product' => false,
			'opm_use_purified_css_wc_archives' => false,
			// extra php
			'opm_use_extra_php_file' => false,
			// remove psi passive listener warning
			'opm_remove_passive_listener' => false,
			// extra
			'opm_remove_block_library_css' => false,
			'opm_remove_wp_global_styles' => false,
			'opm_remove_svg_duotone_filter' => false,
			'opm_disable_jquery_migrate' => false,
			'opm_disable_emoji' => false,
			'opm_disable_embeds' => false,
			'opm_filter_google_fonts' => false,
			'opm_combine_google_fonts' => false,
			'opm_font_display' => '',
			'opm_remove_wc_blocks_css' => false,
			'opm_wc_cart_fragments' => false,
			
			// control media files
			'opm_control_media_files' => false,
			'opm_lazyload' => false,
			'opm_preload_featured_image' => false,
			'opm_add_image_dimensions' => false,
			'opm_add_svg_image_dimensions' => false,
			
			// images - cdn
			'use_free_img_cdn' => false,
			'use_free_img_cdn_statically' => false,
			'use_free_img_cdn_photon' => false,
			'use_free_img_cdn_exclude_list' => '',
			'use_free_img_cdn_compression_quality' => '',
			// rocket async hacks
			'opm_rocket_async_hacks' => false,
			'opm_rocket_async_hacks_home' => false,
			'opm_rocket_async_hacks_pages' => false,
			'opm_rocket_async_hacks_archives' => false,
			'opm_rocket_async_hacks_singular' => false,
			'opm_rocket_async_hacks_wc_product' => false,
			'opm_rocket_async_hacks_wc_archives' => false,
			// enable prefetch
			'opm_use_prefetch' => false,
			// hide js
			/* only use if client request
			'opm_hide_js' => false,
			'opm_hide_js_home' => false,
			'opm_hide_js_product' => false,
			'opm_hide_js_shop' => false,
			'opm_hide_js_product_cat' => false,
			'opm_hide_js_pages' => false,
			'opm_hide_js_single_post' => false,
			'opm_hide_js_front_page_list' => '',
			'opm_hide_js_product_page_list' => '',
			'opm_hide_js_shop_page_list' => '',
			'opm_hide_js_product_cat_page_list' => '',
			'opm_hide_js_pages_list' => '',
			'opm_hide_js_single_post_list' => '',
			*/
			
        );
        
        return apply_filters('opm_setting_fields', $settings);
    }
}