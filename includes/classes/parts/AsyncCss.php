<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class AsyncCSS {
    
    private static $_instance;
    private $options = [];
    private $async_css_front_page;
    private $async_css_pages;
    private $async_css_archives;
    private $async_css_singular;
    private $async_css_wc_product;
    private $async_css_wc_archives;
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
	    
	    $this->async_css_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_async_css_front_page_list'] ?? '')));
        $this->async_css_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_async_css_pages_list'] ?? '')));
        $this->async_css_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_async_css_archives_list'] ?? '')));
        $this->async_css_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_async_css_singular_list'] ?? '')));
        $this->async_css_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_async_css_wc_product_list'] ?? '')));
        $this->async_css_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_async_css_wc_archives_list'] ?? '')));
	    
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
    
    private function processStyle($style, $preload_tags, $async_tags, $noscript_tags) {
        $style->outertext = $preload_tags . $async_tags . $noscript_tags;
    }

    public function Rewrites($htmlRewrite) {
        
        foreach ($htmlRewrite->find("link[rel='stylesheet']") as $style) {
    		
    		$handle = $style->id;
    		$href = $style->href;
    		$media = $style->media;
    		$rel = $style->rel;
    		
    		$preload = "preload";
    		$media_print = "print";
    		$onload = "this.onload=null;this.removeAttribute('media');";
    		
    		$preload_tags = '<link rel="'. esc_html( $preload ) . '" as="style" href="'. esc_url( $href ) . '">';
    		$async_tags = '<link rel="'. esc_html( $rel ) . '" id="'. esc_html( $handle ) . '" href="'. esc_url( $href ) . '" media="'. esc_html( $media_print ) . '" onload="'. wp_kses_data( $onload ) . '">';
    		$noscript_tags = '<noscript><link rel="'. esc_html( $rel ) . '" id="'. esc_html( $handle ) . '" href="'. esc_url( $href ) . '" media="'. esc_html( $media ) . '"></noscript>';
    		
    		// start for frontpage
    	    if ( $this->includeKeywords($style->outertext, $this->async_css_front_page )) {
    			if ( $this->options['opm_async_css_front_page'] && !empty($this->async_css_front_page) ) {
    				if ( is_front_page() ) {
    				    $this->processStyle($style, $preload_tags, $async_tags, $noscript_tags);
    				}
    			}
    		} // ending frontpage
    		
    		// start for pages
    		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
    		} else { $om_custom_pages = ''; }
    	    if ( $this->includeKeywords($style->outertext, $this->async_css_pages )) {
    		    if ( $this->options['opm_async_css_pages'] && !empty($this->async_css_pages) ) {
            	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
            		    $this->processStyle($style, $preload_tags, $async_tags, $noscript_tags);	
            	    }
    	        }
    		} // ending pages
    		
    		// start for archives
    	    if ( $this->includeKeywords($style->outertext, $this->async_css_archives )) {
    			if ( $this->options['opm_async_css_archives'] && !empty($this->async_css_archives) ) {
    				if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $this->processStyle($style, $preload_tags, $async_tags, $noscript_tags);
    					
    				}
    	    	}
    		} // ending archives
    		
    		// start for singular
    	    if ( $this->includeKeywords($style->outertext, $this->async_css_singular )) {
    			if ( $this->options['opm_async_css_singular'] && !empty($this->async_css_singular) ) {
    				if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $this->processStyle($style, $preload_tags, $async_tags, $noscript_tags);
    					
    				}
    			}
    		} // ending singular
    		
    		// run only if no WooCommerce is active.
            if ( $this->is_woocommerce_active ) {
                
		        // start for product
        		if ( $this->includeKeywords($style->outertext, $this->async_css_wc_product )) {
        			if ( $this->options['opm_async_css_wc_product'] && !empty($this->async_css_wc_product) ) {
        				if ( is_product() ) {
        					$this->processStyle($style, $preload_tags, $async_tags, $noscript_tags);
        				}
        	            	}
        		} // ending product
        		
        		// start for wc archives
        		if ( $this->includeKeywords($style->outertext, $this->async_css_wc_archives )) {
        			if ( $this->options['opm_async_css_wc_archives'] && !empty($this->async_css_wc_archives) ) {
        				if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
        					$this->processStyle($style, $preload_tags, $async_tags, $noscript_tags);
        				}
        			}
        		} // ending wc archives
        		
		    }
        
    	}
    	
    	return $htmlRewrite;
    }
    
}