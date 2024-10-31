<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class PreloadFont {
    
    private static $_instance;
    private $options = [];
    private $preload_font_front_page;
    private $preload_font_pages;
    private $preload_font_archives;
    private $preload_font_singular;
    private $preload_font_wc_product;
    private $preload_font_wc_archives;
    private $is_woocommerce_active;

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	public function __construct() {
		
	    $this->options = get_option('opm_options', []);
	    
	    $this->preload_font_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_font_front_page_list'] ?? '')));
        $this->preload_font_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_font_pages_list'] ?? '')));
        $this->preload_font_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_font_archives_list'] ?? '')));
        $this->preload_font_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_font_singular_list'] ?? '')));
        $this->preload_font_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_font_wc_product_list'] ?? '')));
        $this->preload_font_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_font_wc_archives_list'] ?? '')));
	    
	    // Check if WooCommerce is active.
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $this->is_woocommerce_active = is_plugin_active('woocommerce/woocommerce.php');
        
    }
    
    private function processFonts($font_urls, &$htmlRewrite) {
        foreach ($font_urls as $font_url) {
            $extensions = pathinfo(parse_url($font_url, PHP_URL_PATH), PATHINFO_EXTENSION);
            if ( is_plugin_active( 'starterpack/dhiratara.php' ) && get_option('starterpack_options')['starterpack_progresive_minify_html'] ) {
                $preload_tags = "<link rel='preload' as='font' href='" . esc_url($font_url) . "' type='font/" . esc_attr($extensions) . "' crossorigin>";
            } else {
                $preload_tags = PHP_EOL . "<link rel='preload' as='font' href='" . esc_url($font_url) . "' type='font/" . esc_attr($extensions) . "' crossorigin>";
            }
            $htmlRewrite->find('title', 0)->outertext .= $preload_tags;
        }
    }

    public function Rewrites($htmlRewrite) {
    		
 		// start for frontpage
 		if ( $this->options['opm_preload_font_front_page'] && !empty($this->preload_font_front_page) ) {
 			if ( is_front_page() ) {
 			    $font_urls = $this->preload_font_front_page;
					$this->processFonts($font_urls, $htmlRewrite);
 			}
 		} // ending frontpage
 		
 		// start for pages
 		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
 		} else { $om_custom_pages = ''; }
    	    if ( $this->options['opm_preload_font_pages'] && !empty($this->preload_font_pages) ) {
 			if ( is_page( $om_custom_pages ) && !is_front_page() ) {
 			    $font_urls = $this->preload_font_pages;
					$this->processFonts($font_urls, $htmlRewrite);
 			}
 		} // ending pages
 		
 		// start for archives
 		if ( $this->options['opm_preload_font_archives'] && !empty($this->preload_font_archives) ) {
 			if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
 			    $font_urls = $this->preload_font_archives;
					$this->processFonts($font_urls, $htmlRewrite);
 			}
 		} // ending archives
 		
 		// start for singular
 		if ( $this->options['opm_preload_font_singular'] && !empty($this->preload_font_singular) ) {
 			if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
 			    $font_urls = $this->preload_font_singular;
					$this->processFonts($font_urls, $htmlRewrite);
 			}
 		}  // ending singular
 		
 		// run only if no WooCommerce is active.
        if ( $this->is_woocommerce_active ) {
		    
		    // start for product
     		if ( $this->options['opm_preload_font_wc_product'] && !empty($this->preload_font_wc_product) ) {
     			if ( is_product() ) {
     			    $font_urls = $this->preload_font_wc_product;
    					$this->processFonts($font_urls, $htmlRewrite);
     			}
     		}  // ending product
     		
     		// start for wc archives
     		if ( $this->options['opm_preload_font_wc_archives'] && !empty($this->preload_font_wc_archives) ) {
     			if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
     			    $font_urls = $this->preload_font_wc_archives;
    					$this->processFonts($font_urls, $htmlRewrite);
     			}
     		}  // ending wc archives
		    
		}
 		
    	return $htmlRewrite;
    }
    
}