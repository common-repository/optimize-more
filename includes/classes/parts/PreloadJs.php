<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class PreloadJS {
    
    private static $_instance;
    private $options = [];
    private $preload_js_front_page;
    private $preload_js_pages;
    private $preload_js_archives;
    private $preload_js_singular;
    private $preload_js_wc_product;
    private $preload_js_wc_archives;
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
	    
	    $this->preload_js_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_js_front_page_list'] ?? '')));
        $this->preload_js_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_js_pages_list'] ?? '')));
        $this->preload_js_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_js_archives_list'] ?? '')));
        $this->preload_js_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_js_singular_list'] ?? '')));
        $this->preload_js_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_js_wc_product_list'] ?? '')));
        $this->preload_js_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_preload_js_wc_archives_list'] ?? '')));
	    
	    // Check if WooCommerce is active.
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $this->is_woocommerce_active = is_plugin_active('woocommerce/woocommerce.php');
        
    }
    
    private function includeKeywords($content, $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    public function Rewrites($htmlRewrite) {
        
        foreach ($htmlRewrite->find("script[src]") as $script) {
    		
    		$source = $script->src;
    		
    		if ( is_plugin_active( 'starterpack/dhiratara.php' ) && get_option('starterpack_options')['starterpack_progresive_minify_html'] ) {
                $preload_tags = "<link rel='preload' as='script' href='". esc_url( $source ) . "'>";
            } else {
                $preload_tags = PHP_EOL . "<link rel='preload' as='script' href='". esc_url( $source ) . "'>";
            }
    		
    		// start for frontpage
    	    if ( $this->includeKeywords($script->outertext, $this->preload_js_front_page )) {
    			if ( $this->options['opm_preload_js_front_page'] && !empty($this->preload_js_front_page) ) {
    				if ( is_front_page() ) {
    				    $htmlRewrite->find('title', 0)->outertext .= $preload_tags;
    				}
    			}
    		} // ending frontpage
    		
    		// start for pages
    		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
    		} else { $om_custom_pages = ''; }
    	    if ( $this->includeKeywords($script->outertext, $this->preload_js_pages )) {
    		    if ( $this->options['opm_preload_js_pages'] && !empty($this->preload_js_pages) ) {
            	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
            		    $htmlRewrite->find('title', 0)->outertext .= $preload_tags;	
            	    }
    	        }
    		} // ending pages
    		
    		// start for archives
    	    if ( $this->includeKeywords($script->outertext, $this->preload_js_archives )) {
    			if ( $this->options['opm_preload_js_archives'] && !empty($this->preload_js_archives) ) {
    				if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $htmlRewrite->find('title', 0)->outertext .= $preload_tags;
    				}
    	    	}
    		} // ending archives
    		
    		// start for singular
    	    if ( $this->includeKeywords($script->outertext, $this->preload_js_singular )) {
    			if ( $this->options['opm_preload_js_singular'] && !empty($this->preload_js_singular) ) {
    				if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $htmlRewrite->find('title', 0)->outertext .= $preload_tags;
    				}
    			}
    		} // ending singular
    		
    		// run only if no WooCommerce is active.
            if ( $this->is_woocommerce_active ) {
		        
		        // start for product
        		if ( $this->includeKeywords($script->outertext, $this->preload_js_wc_product )) {
        			if ( $this->options['opm_preload_js_wc_product'] && !empty($this->preload_js_wc_product) ) {
        				if ( is_product() ) {
        					$htmlRewrite->find('title', 0)->outertext .= $preload_tags;
        				}
        	       }
        		} // ending product
        		
        		// start for wc archives
        		if ( $this->includeKeywords($script->outertext, $this->preload_js_wc_archives )) {
        			if ( $this->options['opm_preload_js_wc_archives'] && !empty($this->preload_js_wc_archives) ) {
        				if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
        					$htmlRewrite->find('title', 0)->outertext .= $preload_tags;
        				}
        			}
        		} // ending wc archives
		        
		    }

    	}
    	
    	return $htmlRewrite;
    }
    
}