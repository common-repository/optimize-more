<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class DelayCSS {
    
    private static $_instance;
    private $options = [];
    private $delay_css_front_page;
    private $delay_css_pages;
    private $delay_css_singular;
    private $delay_css_wc_product;
    private $delay_css_wc_archives;
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
	    
	    $this->delay_css_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_css_front_page_list'] ?? '')));
        $this->delay_css_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_css_pages_list'] ?? '')));
        $this->delay_css_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_css_archives_list'] ?? '')));
        $this->delay_css_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_css_singular_list'] ?? '')));
        $this->delay_css_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_css_wc_product_list'] ?? '')));
        $this->delay_css_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_css_wc_archives_list'] ?? '')));
	    
	    // Check if WooCommerce is active.
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $this->is_woocommerce_active = is_plugin_active('woocommerce/woocommerce.php');
        
        add_action('wp_print_footer_scripts', [$this, 'printInlineScript'], 999);
        
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
        
        foreach ($htmlRewrite->find("link[rel='stylesheet']") as $style) {
	        
	        $handle = $style->id;
			$href = $style->href;
			$media = $style->media;
			$rel = $style->rel;
			
			$delay_css = "<link rel='". esc_html( $rel ) . "' id='". esc_html( $handle ) . "' data-type='delay' data-href='". esc_url( $href ) . "' media='". esc_html( $media ) . "'/>";
    		
    		// start for frontpage
    	    if ( $this->includeKeywords($style->outertext, $this->delay_css_front_page )) {
    			if ( $this->options['opm_delay_css_front_page'] && !empty($this->delay_css_front_page) ) {
    				if ( is_front_page() ) {
    				    $style->outertext="";
		                $style->outertext .= $delay_css;
    				}
    			}
    		} // ending frontpage
    		
    		// start for pages
    		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
    		} else { $om_custom_pages = ''; }
    	    if ( $this->includeKeywords($style->outertext, $this->delay_css_pages )) {
    		    if ( $this->options['opm_delay_css_pages'] && !empty($this->delay_css_pages) ) {
            	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
            		    $style->outertext="";
		                $style->outertext .= $delay_css;
            	    }
    	        }
    		} // ending pages
    		
    		// start for archives
    	    if ( $this->includeKeywords($style->outertext, $this->delay_css_archives )) {
    			if ( $this->options['opm_delay_css_archives'] && !empty($this->delay_css_archives) ) {
    				if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $style->outertext="";
		                $style->outertext .= $delay_css;
    				}
    	    	}
    		} // ending archives
    		
    		// start for singular
    	    if ( $this->includeKeywords($style->outertext, $this->delay_css_singular )) {
    			if ( $this->options['opm_delay_css_singular'] && !empty($this->delay_css_singular) ) {
    				if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $style->outertext="";
		                $style->outertext .= $delay_css;
    				}
    			}
    		} // ending singular
    		
    		// run only if no WooCommerce is active.
            if ( $this->is_woocommerce_active ) {
		        
		        // start for product
        		if ( $this->includeKeywords($style->outertext, $this->delay_css_wc_product )) {
        			if ( $this->options['opm_delay_css_wc_product'] && !empty($this->delay_css_wc_product) ) {
        				if ( is_product() ) {
        					$style->outertext="";
    		                $style->outertext .= $delay_css;
        				}
        	        }
        		} // ending product
        		
        		// start for wc archives
        		if ( $this->includeKeywords($style->outertext, $this->delay_css_wc_archives )) {
        			if ( $this->options['opm_delay_css_wc_archives'] && !empty($this->delay_css_wc_archives) ) {
        				if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
        					$style->outertext="";
    		                $style->outertext .= $delay_css;
        				}
        			}
        		} // ending wc archives
		        
		    }
        
    	}
    	
    	return $htmlRewrite;
    }
    
    private function inlineScript() {
        
        $om_delay_css_time = apply_filters( 'om_delay_css_time', '5e6' );
		
	    $delay_css_script =
	        'const loadStylesTimer=setTimeout(loadStyles,' . esc_attr( $om_delay_css_time ) . '),opmInteractionEvents=["mouseover","mousemove","mousedown","mouseup","click","keydown","touchstart","touchmove","wheel"];function triggerStyleLoader(){loadStyles(),clearTimeout(loadStylesTimer),opmInteractionEvents.forEach(function(e){window.removeEventListener(e,triggerStyleLoader,{passive:!0})})}function loadStyles(){document.querySelectorAll("link[data-type=delay]").forEach(function(e){e.setAttribute("href",e.getAttribute("data-href"))})}opmInteractionEvents.forEach(function(e){window.addEventListener(e,triggerStyleLoader,{passive:!0})});';
	
	    $delay_css_script_handle = 'om-delay-css';
	
	    $delay_css_inline_script = '<script id="%s">%s</script>' . PHP_EOL;
	    
	    printf($delay_css_inline_script, esc_html($delay_css_script_handle), htmlspecialchars_decode(wp_kses_data($delay_css_script)));
	    
    }
    
    public function printInlineScript() {
	
		if (is_user_logged_in()) {
			return;
		}
		
		// Check if no WooCommerce is active, return.
        if ( $this->is_woocommerce_active ) {
			if ( is_cart() || is_checkout() || is_wc_endpoint_url() || is_account_page() ) {
				return;
			}
		}
	    
	    // start for frontpage
    	if ( $this->options['opm_delay_css_front_page'] && !empty($this->delay_css_front_page) ) {
    	    if ( is_front_page() ) {
    		    $this->inlineScript();
    	    }
    	} // ending frontpage
		
		// start for pages
		if ( !empty($this->options['opm_custom_pages_id'] )) {
		$om_custom_pages = $this->options['opm_custom_pages_id'];
		$om_custom_pages = explode(",", $om_custom_pages);
		} else { $om_custom_pages = ''; }
		if ( $this->options['opm_delay_css_pages'] && !empty($this->delay_css_pages) ) {
            if ( is_page( $om_custom_pages ) && !is_front_page() ) {
        	    $this->inlineScript();
			}
		} // ending pages
		
		// start for archives
		if ( $this->options['opm_delay_css_archives'] && !empty($this->delay_css_archives) ) {
			if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
			    $this->inlineScript();
			}
		} // ending archives
		
		// start for singular
		if ( $this->options['opm_delay_css_singular'] && !empty($this->delay_css_singular) ) {
			if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
				$this->inlineScript();	
			}
		} // ending singular
		
		// run only if no WooCommerce is active.
        if ( $this->is_woocommerce_active ) {
		    
		    // start for product
    		if ( $this->options['opm_delay_css_wc_product'] && !empty($this->delay_css_wc_product) ) {
    			if ( is_product() ) {
    				$this->inlineScript();	
    		    }
    		} // ending product
    		
    		// start for wc archives
    		if ( $this->options['opm_delay_css_wc_archives'] && !empty($this->delay_css_wc_archives) ) {
    			if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
    				$this->inlineScript();	
    			}
    		} // ending wc archives
    		
		}

	}
    
}