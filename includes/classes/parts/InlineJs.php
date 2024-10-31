<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class InlineJS {
    
    private static $_instance;
    private $options = [];
    private $inline_js_front_page;
    private $inline_js_pages;
    private $inline_js_archives;
    private $inline_js_singular;
    private $inline_js_wc_product;
    private $inline_js_wc_archives;
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
	    
	    $this->inline_js_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_js_front_page_list'] ?? '')));
        $this->inline_js_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_js_pages_list'] ?? '')));
        $this->inline_js_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_js_archives_list'] ?? '')));
        $this->inline_js_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_js_singular_list'] ?? '')));
        $this->inline_js_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_js_wc_product_list'] ?? '')));
        $this->inline_js_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_js_wc_archives_list'] ?? '')));
	    
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
    
    private function processScript($script, $handle, $href) {
		
		// Get the js file contents using wp_remote_get
		$response = wp_remote_get($href, array(
		  'headers' => array(
		    'Accept' => 'application/javascript'
		  ),
		));
		
		if (is_wp_error($response)) {
		    $error_message = $response->get_error_message();
		    return $error_message;
		}
		
		$om_inline_js = wp_remote_retrieve_body($response);
		
		// Replace relative URLs in the js file with absolute URLs
		$href_parts = parse_url($href);
		$href_path = $href_parts['path'];
		if (substr($href_path, -1) != '/') {
		  $href_path = dirname($href_path) . '/';
		}
		$absolute_url = $href_parts['scheme'] . '://' . $href_parts['host'] . $href_path;
		$om_inline_js = preg_replace_callback('/url\((\'|")?(?P<url>[^\'")]*)\1?\)/i', function($matches) use ($absolute_url) {
		  $url = $matches['url'];
		  if (strpos($url, 'http') !== 0) {
		    $url = $absolute_url . $url;
		  }
		  return 'url("' . $url . '")';
		}, $om_inline_js);
		
        $om_inline_js = preg_replace(['/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/','/\>[^\S ]+/s','/[^\S ]+\</s','#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si'],['','>','<','$1$2$3$4$5$6$7'],
    		$om_inline_js);
		
		$inline_js = sprintf(
            "<script id='om-inlined-%s' from='%s'>%s</script>",
            esc_attr($handle),
            esc_url($href),
            $om_inline_js
        );
	    
	    $script->outertext="";
	    $script->outertext .= $inline_js;
	}

    public function Rewrites($htmlRewrite) {
        
        foreach ($htmlRewrite->find("script[src]") as $script) {
    		
    		$handle = $script->id;
    		$href = $script->src;
    		
    		// start for frontpage
    	    if ( $this->includeKeywords($script->outertext, $this->inline_js_front_page )) {
    			if ( $this->options['opm_inline_js_front_page'] && !empty($this->inline_js_front_page) ) {
    				if ( is_front_page() ) {
    				    $this->processScript($script, $handle, $href);
    				}
    			}
    		} // ending frontpage
    		
    		// start for pages
    		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
    		} else { $om_custom_pages = ''; }
    		
    	    if ( $this->includeKeywords($script->outertext, $this->inline_js_pages )) {
    		    if ( $this->options['opm_inline_js_pages'] && !empty($this->inline_js_pages) ) {
            	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
            		    $this->processScript($script, $handle, $href);
            	    }
    	        }
    		} // ending pages
    		
    		// start for archives
    	    if ( $this->includeKeywords($script->outertext, $this->inline_js_archives )) {
    			if ( $this->options['opm_inline_js_archives'] && !empty($this->inline_js_archives) ) {
    				if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $this->processScript($script, $handle, $href);
    				}
    	    	}
    		} // ending archives
    		
    		// start for singular
    	    if ( $this->includeKeywords($script->outertext, $this->inline_js_singular )) {
    			if ( $this->options['opm_inline_js_singular'] && !empty($this->inline_js_singular) ) {
    				if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $this->processScript($script, $handle, $href);
    				}
    			}
    		} // ending singular
    		
    		// run only if no WooCommerce is active.
            if ( $this->is_woocommerce_active ) {
		        
		        // start for product
        		if ( $this->includeKeywords($script->outertext, $this->inline_js_wc_product )) {
        			if ( $this->options['opm_inline_js_wc_product'] && !empty($this->inline_js_wc_product) ) {
        				if ( is_product() ) {
        					$this->processScript($script, $handle, $href);
        				}
        	        }
        		} // ending product
        		
        		// start for wc archives
        		if ( $this->includeKeywords($script->outertext, $this->inline_js_wc_archives )) {
        			if ( $this->options['opm_inline_js_wc_archives'] && !empty($this->inline_js_wc_archives) ) {
        				if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
        					$this->processScript($script, $handle, $href);
        				}
        			}
        		} // ending wc archives
		        
		    }

    	}
    	
    	return $htmlRewrite;
    }
    
}