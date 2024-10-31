<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class RemoveJS {
    
    private static $_instance;
    private $options = [];
    private $remove_js_front_page;
    private $remove_js_pages;
    private $remove_js_singular;
    private $remove_js_wc_product;
    private $remove_js_wc_archives;
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
	    
	    $this->remove_js_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_remove_js_front_page_list'] ?? '')));
        $this->remove_js_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_remove_js_pages_list'] ?? '')));
        $this->remove_js_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_remove_js_archives_list'] ?? '')));
        $this->remove_js_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_remove_js_singular_list'] ?? '')));
        $this->remove_js_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_remove_js_wc_product_list'] ?? '')));
        $this->remove_js_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_remove_js_wc_archives_list'] ?? '')));
	    
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
        
        foreach ($htmlRewrite->find("script[!type],script[type='text/javascript']") as $script) {
    		
    		// start for frontpage
    	    if ( $this->includeKeywords($script->outertext, $this->remove_js_front_page )) {
    			if ( $this->options['opm_remove_js_front_page'] && !empty($this->remove_js_front_page) ) {
    				if ( is_front_page() ) {
    				    $script->outertext="";
    				}
    			}
    		} // ending frontpage
    		
    		// start for pages
    		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
    		} else { $om_custom_pages = ''; }
    	    if ( $this->includeKeywords($script->outertext, $this->remove_js_pages )) {
    		    if ( $this->options['opm_remove_js_pages'] && !empty($this->remove_js_pages) ) {
            	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
            		    $script->outertext="";
            	    }
    	        }
    		} // ending pages
    		
    		// start for archives
    	    if ( $this->includeKeywords($script->outertext, $this->remove_js_archives )) {
    			if ( $this->options['opm_remove_js_archives'] && !empty($this->remove_js_archives) ) {
    				if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $script->outertext="";
    				}
    	    	}
    		} // ending archives
    		
    		// start for singular
    	    if ( $this->includeKeywords($script->outertext, $this->remove_js_singular )) {
    			if ( $this->options['opm_remove_js_singular'] && !empty($this->remove_js_singular) ) {
    				if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $script->outertext="";
    				}
    			}
    		} // ending singular
    		
    		// run only if no WooCommerce is active.
            if ( $this->is_woocommerce_active ) {
		        
		        // start for product
        		if ( $this->includeKeywords($script->outertext, $this->remove_js_wc_product )) {
        			if ( $this->options['opm_remove_js_wc_product'] && !empty($this->remove_js_wc_product) ) {
        				if ( is_product() ) {
        					$script->outertext="";
        				}
        	        }
        		} // ending product
        		
        		// start for wc archives
        		if ( $this->includeKeywords($script->outertext, $this->remove_js_wc_archives )) {
        			if ( $this->options['opm_remove_js_wc_archives'] && !empty($this->remove_js_wc_archives) ) {
        				if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
        					$script->outertext="";
        				}
        			}
        		} // ending wc archives
		        
		    }

    	}
    	
    	return $htmlRewrite;
    }
        
}