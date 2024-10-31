<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class DelayJS {
    
    private static $_instance;
    private $options = [];
    private $delay_js_front_page;
    private $delay_js_pages;
    private $delay_js_singular;
    private $delay_js_wc_product;
    private $delay_js_wc_archives;
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
	    
	    $this->delay_js_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_js_front_page_list'] ?? '')));
        $this->delay_js_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_js_pages_list'] ?? '')));
        $this->delay_js_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_js_archives_list'] ?? '')));
        $this->delay_js_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_js_singular_list'] ?? '')));
        $this->delay_js_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_js_wc_product_list'] ?? '')));
        $this->delay_js_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_delay_js_wc_archives_list'] ?? '')));
	    
	    // Check if WooCommerce is active.
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $this->is_woocommerce_active = is_plugin_active('woocommerce/woocommerce.php');
        
        add_action('wp_print_footer_scripts', [$this, 'printInlineScript'], 999);
        
    }
    
    private function Excludes($script, $delay_js_exclude) {
        foreach ($delay_js_exclude as $exclude) {
            if ($exclude && strpos($script, $exclude) !== false) {
                return true;
            }
        }
        return false;
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
        $script->setAttribute("data-type", "delay");
        if ($script->getAttribute("src")) {
            $script->setAttribute("data-src", $script->getAttribute("src"));
            $script->removeAttribute("src");
        } else {
            $script->setAttribute("data-src", "data:text/javascript;base64," . base64_encode($script->innertext));
            $script->innertext = "";
        }
    }

    public function Rewrites($htmlRewrite) {
        
        foreach ($htmlRewrite->find("script[!type],script[type='text/javascript']")  as $script ) {
            
            $delay_js_exclude_list_default = apply_filters('delay_js_exclude_list_default', array(
                    'om-prefetch-js',
                    'opm_getCookie',
                ));
            
    		
    		// start for frontpage
            $delay_js_exclude_list_front_page = apply_filters('delay_js_exclude_list_front_page', array());
            
    	    if ( $this->includeKeywords($script->outertext, $this->delay_js_front_page )) {
    			if ( $this->options['opm_delay_js_front_page'] && !empty($this->delay_js_front_page) ) {
    				if ( is_front_page() ) {
    				    
    				    $delay_js_exclude = array_merge( $delay_js_exclude_list_default,
    				        $delay_js_exclude_list_front_page
    				    );
	
	                    if ($this->Excludes($script, $delay_js_exclude)) {
                            continue;
                        }
    				    
    				    $this->processScript($script);
    				}
    			}
    		} // ending frontpage
    		
    		// start for pages
    		$delay_js_exclude_list_pages = apply_filters('delay_js_exclude_list_pages', array());
    		
    		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
    		} else { $om_custom_pages = ''; }
    	    if ( $this->includeKeywords($script->outertext, $this->delay_js_pages )) {
    		    if ( $this->options['opm_delay_js_pages'] && !empty($this->delay_js_pages) ) {
            	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
            	        
            	        $delay_js_exclude = array_merge( $delay_js_exclude_list_default,
    				        $delay_js_exclude_list_pages
    				    );
	
	                    if ($this->Excludes($script, $delay_js_exclude)) {
                            continue;
                        }
            	        
            		    $this->processScript($script);
            	    }
    	        }
    		} // ending pages
    		
    		// start for archives
    		$delay_js_exclude_list_archives = apply_filters('delay_js_exclude_list_archives', array());
    		
    	    if ( $this->includeKeywords($script->outertext, $this->delay_js_archives )) {
    			if ( $this->options['opm_delay_js_archives'] && !empty($this->delay_js_archives) ) {
    				if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    
    				    $delay_js_exclude = array_merge( $delay_js_exclude_list_default,
    				        $delay_js_exclude_list_archives
    				    );
	
	                    if ($this->Excludes($script, $delay_js_exclude)) {
                            continue;
                        }
    				    
    				    $this->processScript($script);
    				}
    	    	}
    		} // ending archives
    		
    		// start for singular
    		$delay_js_exclude_list_singular = apply_filters('delay_js_exclude_list_singular', array());
    		
    	    if ( $this->includeKeywords($script->outertext, $this->delay_js_singular )) {
    			if ( $this->options['opm_delay_js_singular'] && !empty($this->delay_js_singular) ) {
    				if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    
    				    $delay_js_exclude = array_merge( $delay_js_exclude_list_default,
    				        $delay_js_exclude_list_singular
    				    );
	
	                    if ($this->Excludes($script, $delay_js_exclude)) {
                            continue;
                        }
    				    
    				    $this->processScript($script);
    				}
    			}
    		} // ending singular
    		
    		// run only if no WooCommerce is active.
            if ( $this->is_woocommerce_active ) {
		        
		        // start for product
        		$delay_js_exclude_list_wc_product = apply_filters('delay_js_exclude_list_wc_product', array());
        		
        		if ( $this->includeKeywords($script->outertext, $this->delay_js_wc_product )) {
        			if ( $this->options['opm_delay_js_wc_product'] && !empty($this->delay_js_wc_product) ) {
        				if ( is_product() ) {
        				    
        				    $delay_js_exclude = array_merge( $delay_js_exclude_list_default,
        				        $delay_js_exclude_list_wc_product
        				    );
    	
    	                    if ($this->Excludes($script, $delay_js_exclude)) {
                                continue;
                            }
        				    
        					$this->processScript($script);
        				}
        	        }
        		} // ending product
        		
        		// start for wc archives
        		$delay_js_exclude_list_wc_archives = apply_filters('delay_js_exclude_list_wc_archives', array());
        		
        		if ( $this->includeKeywords($script->outertext, $this->delay_js_wc_archives )) {
        			if ( $this->options['opm_delay_js_wc_archives'] && !empty($this->delay_js_wc_archives) ) {
        				if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
        				    
        				    $delay_js_exclude = array_merge( $delay_js_exclude_list_default,
        				        $delay_js_exclude_list_wc_archives
        				    );
    	
    	                    if ($this->Excludes($script, $delay_js_exclude)) {
                                continue;
                            }
        					$this->processScript($script);
        				}
        			}
        		} // ending wc archives
		        
		    }
        
    	}
    	
    	return $htmlRewrite;
    }
    
    private function inlineScript() {
        
        $om_delay_js_time = apply_filters( 'om_delay_js_time', '5e6' );

        $delay_js_script =
            'const loadScriptsTimer=setTimeout(loadScripts,' . esc_attr( $om_delay_js_time ) . ');function triggerScriptLoader(){loadScripts(),clearTimeout(loadScriptsTimer),opmInteractionEvents.forEach(function(e){window.removeEventListener(e,triggerScriptLoader,{passive:!0})})}function loadScripts(){document.querySelectorAll("script[data-type=delay]").forEach(function(e){e.setAttribute("src",e.getAttribute("data-src"))})}opmInteractionEvents=["mouseover","mousemove","mousedown","mouseup","click","keydown","touchstart","touchmove","wheel"],opmInteractionEvents.forEach(function(e){window.addEventListener(e,triggerScriptLoader,{passive:!0})});';
	   
	    $delay_js_script_handle = 'om-delay-js';
	
	    $delay_js_inline_script = '<script id="%s">%s</script>' . PHP_EOL;
	    
	    printf($delay_js_inline_script, esc_html($delay_js_script_handle), htmlspecialchars_decode(wp_kses_data($delay_js_script)));
	    
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
    	if ( $this->options['opm_delay_js_front_page'] && !empty($this->delay_js_front_page) ) {
    	    if (is_front_page()) {
	            $this->inlineScript();
	        }
    	} // ending frontpage
		
		// start for pages
		if ( !empty($this->options['opm_custom_pages_id'] )) {
		$om_custom_pages = $this->options['opm_custom_pages_id'];
		$om_custom_pages = explode(",", $om_custom_pages);
		} else { $om_custom_pages = ''; }
		if ( $this->options['opm_delay_js_pages'] && !empty($this->delay_js_pages) ) {
                if ( is_page( $om_custom_pages ) && !is_front_page() ) {
        	    $this->inlineScript();
			}
		} // ending pages
		
		// start for archives
		if ( $this->options['opm_delay_js_archives'] && !empty($this->delay_js_archives) ) {
			if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
				$this->inlineScript();
			}
		} // ending archives
		
		// start for singular
		if ( $this->options['opm_delay_js_singular'] && !empty($this->delay_js_singular) ) {
			if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
				$this->inlineScript();
			}
		} // ending singular
		
		// run only if no WooCommerce is active.
        if ( $this->is_woocommerce_active ) {
            
		    // start for product
    		if ( $this->options['opm_delay_js_wc_product'] && !empty($this->delay_js_wc_product) ) {
    			if ( is_product() ) {
    				$this->inlineScript();
    		    }
    		} // ending product
    		
    		// start for wc archives
    		if ( $this->options['opm_delay_js_wc_archives'] && !empty($this->delay_js_wc_archives) ) {
    			if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
    				$this->inlineScript();	
    			}
    		} // ending wc archives
    		
		}

	}
    
}