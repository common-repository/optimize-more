<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class AdvDeferJS {
    
    private static $_instance;
    private $options = [];
    private $adv_defer_js_front_page;
    private $adv_defer_js_pages;
    private $adv_defer_js_archives;
    private $adv_defer_js_singular;
    private $adv_defer_js_wc_product;
    private $adv_defer_js_wc_archives;
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
	    
	    $this->adv_defer_js_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_adv_defer_js_front_page_list'] ?? '')));
        $this->adv_defer_js_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_adv_defer_js_pages_list'] ?? '')));
        $this->adv_defer_js_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_adv_defer_js_archives_list'] ?? '')));
        $this->adv_defer_js_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_adv_defer_js_singular_list'] ?? '')));
        $this->adv_defer_js_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_adv_defer_js_wc_product_list'] ?? '')));
        $this->adv_defer_js_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_adv_defer_js_wc_archives_list'] ?? '')));
	    
	    // Check if WooCommerce is active.
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $this->is_woocommerce_active = is_plugin_active('woocommerce/woocommerce.php');
        
        add_action('wp_head', [$this, 'printInlineScript'], 1);
        
    }
    
    private function includeKeywords($content, $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
    
    private function processScript($script) {
        $script->setAttribute("data-type", "defer");
        if ($script->getAttribute("src")) {
            $script->setAttribute("data-src", $script->getAttribute("src"));
            $script->removeAttribute("src");
        } else {
            $script->setAttribute("data-src", "data:text/javascript;base64," . base64_encode($script->innertext));
            $script->innertext = "";
        }
    }

    public function Rewrites($htmlRewrite) {
        
        foreach ($htmlRewrite->find("script[src]") as $script) {
    		
    		// start for frontpage
    	    if ( $this->includeKeywords($script->outertext, $this->adv_defer_js_front_page )) {
    			if ( $this->options['opm_adv_defer_js_front_page'] && !empty($this->adv_defer_js_front_page) ) {
    				if ( is_front_page() ) {
    				    $this->processScript($script);
    				}
    			}
    		} // ending frontpage
    		
    		// start for pages
    		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
    		} else { $om_custom_pages = ''; }
    	    if ( $this->includeKeywords($script->outertext, $this->adv_defer_js_pages )) {
    		    if ( $this->options['opm_adv_defer_js_pages'] && !empty($this->adv_defer_js_pages) ) {
            	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
            		    $this->processScript($script);	
            	    }
    	        }
    		} // ending pages
    		
    		// start for archives
    	    if ( $this->includeKeywords($script->outertext, $this->adv_defer_js_archives )) {
    			if ( $this->options['opm_adv_defer_js_archives'] && !empty($this->adv_defer_js_archives) ) {
    				if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $this->processScript($script);
    					
    				}
    	    	}
    		} // ending archives
    		
    		// start for singular
    	    if ( $this->includeKeywords($script->outertext, $this->adv_defer_js_singular )) {
    			if ( $this->options['opm_adv_defer_js_singular'] && !empty($this->adv_defer_js_singular) ) {
    				if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $this->processScript($script);
    					
    				}
    			}
    		} // ending singular
    		
    		// run only if no WooCommerce is active.
            if ( $this->is_woocommerce_active ) {
		        
		        // start for product
        		if ( $this->includeKeywords($script->outertext, $this->adv_defer_js_wc_product )) {
        			if ( $this->options['opm_adv_defer_js_wc_product'] && !empty($this->adv_defer_js_wc_product) ) {
        				if ( is_product() ) {
        					$this->processScript($script);
        				}
        	            	}
        		} // ending product
        		
        		// start for wc archives
        		if ( $this->includeKeywords($script->outertext, $this->adv_defer_js_wc_archives )) {
        			if ( $this->options['opm_adv_defer_js_wc_archives'] && !empty($this->adv_defer_js_wc_archives) ) {
        				if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
        					$this->processScript($script);
        				}
        			}
        		} // ending wc archives
		        
		    }
    		
        
    	}
    	
    	return $htmlRewrite;
    }
    
    private function inlineScript() {
        
        $adv_defer_js_script =
	        'function deferLoadJS(){var a=document.querySelectorAll("script[data-type=defer]");for(var b=0;b<a.length;b++){var c=document.createElement("script");c.src=a[b].getAttribute("data-src"),a[b].removeAttribute("data-src"),document.body.appendChild(c)}}window.addEventListener?window.addEventListener("load",deferLoadJS,!1):window.attachEvent?window.attachEvent("onload",deferLoadJS):window.onload=deferLoadJS';
	
	    $adv_defer_js_script_handle = 'om-adv-defer-js';
	
	    $adv_defer_js_inline_script = '<script async id="%s">%s</script>' . PHP_EOL;
	    
	    printf($adv_defer_js_inline_script, esc_html($adv_defer_js_script_handle), htmlspecialchars_decode(wp_kses_data($adv_defer_js_script)));	
	    
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
    	if ( $this->options['opm_adv_defer_js_front_page'] && !empty($this->adv_defer_js_front_page) ) {
    	    if ( is_front_page() ) {
    		    $this->inlineScript();
    	    }
    	} // ending frontpage
		
		// start for pages
		if ( !empty($this->options['opm_custom_pages_id'] )) {
		$om_custom_pages = $this->options['opm_custom_pages_id'];
		$om_custom_pages = explode(",", $om_custom_pages);
		} else { $om_custom_pages = ''; }
		if ( $this->options['opm_adv_defer_js_pages'] && !empty($this->adv_defer_js_pages) ) {
            if ( is_page( $om_custom_pages ) && !is_front_page() ) {
        	    $this->inlineScript();
			}
		} // ending pages
		
		// start for archives
		if ( $this->options['opm_adv_defer_js_archives'] && !empty($this->adv_defer_js_archives) ) {
			if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
				    $this->inlineScript();
			}
		} // ending archives
		
		// start for singular
		if ( $this->options['opm_adv_defer_js_singular'] && !empty($this->adv_defer_js_singular) ) {
			if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
				    $this->inlineScript();
			}
		} // ending singular
		
		// Check if no WooCommerce is active, return.
        if ( !$this->is_woocommerce_active ) {
		    return;
		}
		
		// start for product
		if ( $this->options['opm_adv_defer_js_wc_product'] && !empty($this->adv_defer_js_wc_product) ) {
			if ( is_product() ) {
				$this->inlineScript();	
			}
		} // ending product
		
		// start for wc archives
		if ( $this->options['opm_adv_defer_js_wc_archives'] && !empty($this->adv_defer_js_wc_archives) ) {
			if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
				$this->inlineScript();	
			}
		} // ending wc archives
	    
	}
    
}