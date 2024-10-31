<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class InlineCSS {
    
    private static $_instance;
    private $options = [];
    private $inline_css_front_page;
    private $inline_css_pages;
    private $inline_css_archives;
    private $inline_css_singular;
    private $inline_css_wc_product;
    private $inline_css_wc_archives;
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
	    
	    $this->inline_css_front_page = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_css_front_page_list'] ?? '')));
        $this->inline_css_pages = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_css_pages_list'] ?? '')));
        $this->inline_css_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_css_archives_list'] ?? '')));
        $this->inline_css_singular = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_css_singular_list'] ?? '')));
        $this->inline_css_wc_product = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_css_wc_product_list'] ?? '')));
        $this->inline_css_wc_archives = array_filter(array_map('trim', explode("\n", $this->options['opm_inline_css_wc_archives_list'] ?? '')));
	    
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
    
    private function processStyle($style, $handle, $href) {
		
		if (strpos($href, 'fonts.googleapis.com') !== false) {
		    
		    $href = str_replace('&#038;', '&', $href);
				    
            // Get the CSS file contents using wp_remote_get
    		$response = wp_remote_get($href, array(
    		  'headers' => array(
    		    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
    		    'Accept' => 'text/css'
    		  ),
    		));
    		
    		if (is_wp_error($response)) {
    		    $error_message = $response->get_error_message();
    		    return $error_message;
    		}
    		
    		$om_inline_css = wp_remote_retrieve_body($response);
    		
        } else {
    		
    		// Get the CSS file contents using wp_remote_get
    		$response = wp_remote_get($href, array(
    		  'headers' => array(
    		    'Accept' => 'text/css'
    		  ),
    		));
    		
    		if (is_wp_error($response)) {
    		    $error_message = $response->get_error_message();
    		    return $error_message;
    		}
    		
    		$om_inline_css = wp_remote_retrieve_body($response);
    		
    		// Replace relative URLs in the CSS file with absolute URLs
    		$href_parts = parse_url($href);
    		$href_path = $href_parts['path'];
    		if (substr($href_path, -1) != '/') {
    		  $href_path = dirname($href_path) . '/';
    		}
    		$absolute_url = $href_parts['scheme'] . '://' . $href_parts['host'] . $href_path;
    		$om_inline_css = preg_replace_callback('/url\((\'|")?(?P<url>[^\'")]*)\1?\)/i', function($matches) use ($absolute_url) {
    		  $url = $matches['url'];
    		  if (strpos($url, 'http') !== 0) {
    		    $url = $absolute_url . $url;
    		  }
    		  return 'url("' . $url . '")';
    		}, $om_inline_css);
    		
    		// Add font-display: swap to @font-face declarations without it
    		$om_inline_css = preg_replace_callback('/@font-face[^{]*\{[^}]*}/i', function($matches) {
    		  $declaration = $matches[0];
    		  if (strpos($declaration, 'font-display') === false) {
    		    // Add font-display: swap to declaration
    		    $declaration .= 'font-display: swap;';
    		  }
    		  return $declaration;
    		}, $om_inline_css);

        }
		
        $om_inline_css = preg_replace(
            [
        		// Remove comment(s)
        		'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
        		// Remove unused white-space(s)
        		'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
        		// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
        		'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
        		// Replace `:0 0 0 0` with `:0`
        		'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
        		// Replace `background-position:0` with `background-position:0 0`
        		'#(background-position):0(?=[;\}])#si',
        		// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
        		'#(?<=[\s:,\-])0+\.(\d+)#s',
        		// Minify string value
        		'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])(data:image[^\'"]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])(data:image[^\'"]*?)\3(\))#si',
        		// Minify HEX color code
        		'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
        		// Replace `(border|outline):none` with `(border|outline):0`
        		'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
        		// Remove empty selector(s)
        		'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
    		],
    		[
        		'$1',
        		'$1$2$3$4$5$6$7',
        		'$1',
        		':0',
        		'$1:0 0',
        		'.$1',
        		'$1$3',
        		'$1$2$4$5',
        		'$1$2$3',
        		'$1:0',
        		'$1$2'
    		],
    		$om_inline_css);
					
		//$inline_css = "<style id='om-inlined-". esc_html( $handle ) . "' from='". esc_url( $href ) . "'>". wp_strip_all_tags($om_inline_css) . "</style>";
        $inline_css = sprintf(
            "<style id='om-inlined-%s' from='%s'>%s</style>",
            esc_html($handle),
            esc_url($href),
            wp_strip_all_tags($om_inline_css)
        );
	    
	    $style->outertext="";
	    $style->outertext .= $inline_css;
	}

    public function Rewrites($htmlRewrite) {
        
        foreach ($htmlRewrite->find("link[rel='stylesheet']") as $style) {
    		
    		$handle = $style->id;
    		
    		$href = $style->href;
    		
    		// start for frontpage
    	    if ( $this->includeKeywords($style->outertext, $this->inline_css_front_page )) {
    			if ( $this->options['opm_inline_css_front_page'] && !empty($this->inline_css_front_page) ) {
    				if ( is_front_page() ) {
    				    $this->processStyle($style, $handle, $href);
    				}
    			}
    		} // ending frontpage
    		
    		// start for pages
    		if ( !empty($this->options['opm_custom_pages_id'] )) {
    	    	$om_custom_pages = $this->options['opm_custom_pages_id'];
    	    	$om_custom_pages = explode(",", $om_custom_pages);
    		} else { $om_custom_pages = ''; }
    		
    	    if ( $this->includeKeywords($style->outertext, $this->inline_css_pages )) {
    		    if ( $this->options['opm_inline_css_pages'] && !empty($this->inline_css_pages) ) {
            	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
            		    $this->processStyle($style, $handle, $href);
            	    }
    	        }
    		} // ending pages
    		
    		// start for archives
    	    if ( $this->includeKeywords($style->outertext, $this->inline_css_archives )) {
    			if ( $this->options['opm_inline_css_archives'] && !empty($this->inline_css_archives) ) {
    				if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $this->processStyle($style, $handle, $href);
    				}
    	    	}
    		} // ending archives
    		
    		// start for singular
    	    if ( $this->includeKeywords($style->outertext, $this->inline_css_singular )) {
    			if ( $this->options['opm_inline_css_singular'] && !empty($this->inline_css_singular) ) {
    				if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    				    $this->processStyle($style, $handle, $href);
    				}
    			}
    		} // ending singular
    		
    		// run only if no WooCommerce is active.
            if ( $this->is_woocommerce_active ) {
		        
		        // start for product
        		if ( $this->includeKeywords($style->outertext, $this->inline_css_wc_product )) {
        			if ( $this->options['opm_inline_css_wc_product'] && !empty($this->inline_css_wc_product) ) {
        				if ( is_product() ) {
        					$this->processStyle($style, $handle, $href);
        				}
        	        }
        		} // ending product
        		
        		// start for wc archives
        		if ( $this->includeKeywords($style->outertext, $this->inline_css_wc_archives )) {
        			if ( $this->options['opm_inline_css_wc_archives'] && !empty($this->inline_css_wc_archives) ) {
        				if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
        					$this->processStyle($style, $handle, $href);
        				}
        			}
        		} // ending wc archives
		        
		    }

    	}
    	
    	return $htmlRewrite;
    }
    
}