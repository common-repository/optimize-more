<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class NonParser {
    
    private $options = [];
    
    public function __construct() {
        
        $this->options = get_option('opm_options', []);
		
		add_action('wp_enqueue_scripts', [$this, 'removeStylesAndScripts'], PHP_INT_MAX );
        
        if ( $this->options['opm_remove_block_library_css'] ) {
            add_action('wp_enqueue_scripts', [$this, 'loadBlockLibraryCssConditionally'] );
        }
        
        if ( $this->options['opm_remove_svg_duotone_filter'] ) {
            add_action('init', [$this, 'removeWpSvgDuotoneFilter'] );
        }
        
        if ( $this->options['opm_disable_jquery_migrate'] ) {
            add_action('wp_default_scripts', [$this, 'removeJqueryMigrate'], PHP_INT_MAX );
        }
        
        if ( $this->options['opm_disable_emoji'] ) {
            $this->disableEmojis();
        }
        
        if ( $this->options['opm_disable_embeds'] ) {
            $this->disableEmbeds();
        }
        
        if ( $this->options['opm_use_prefetch'] ) {
            add_action('wp_print_footer_scripts',  [$this, 'usePrefetch'], 99 );
        }
        
        if ( $this->options['opm_remove_passive_listener'] ) {
            add_action('wp_head',  [$this, 'removePassiveListener'], PHP_INT_MAX );
        }
        
        if ( $this->options['opm_wc_cart_fragments'] ) {
            add_action('wp_enqueue_scripts',  [$this, 'optimizeWcCartFragments'], PHP_INT_MAX );
        }
        
        if ( $this->options['opm_combine_google_fonts'] ) {
            $this->combineGoogleFontsInit();
        }
		
    }
    
    public function removePassiveListener() {
        
        if (is_user_logged_in()) {
    		return;
    	}
    	
    	$script = "!function(e){'function'==typeof define&&define.amd?define(e):e()}(function(){var e,t=['scroll','wheel','touchstart','touchmove','touchenter','touchend','touchleave','mouseout','mouseleave','mouseup','mousedown','mousemove','mouseenter','mousewheel','mouseover'];if(function(){var e=!1;try{var t=Object.defineProperty({},'passive',{get:function(){e=!0}});window.addEventListener('test',null,t),window.removeEventListener('test',null,t)}catch(e){}return e}()){var n=EventTarget.prototype.addEventListener;e=n,EventTarget.prototype.addEventListener=function(n,o,r){var i,s='object'==typeof r&&null!==r,u=s?r.capture:r;(r=s?function(e){var t=Object.getOwnPropertyDescriptor(e,'passive');return t&&!0!==t.writable&&void 0===t.set?Object.assign({},e):e}(r):{}).passive=void 0!==(i=r.passive)?i:-1!==t.indexOf(n)&&!0,r.capture=void 0!==u&&u,e.call(this,n,o,r)},EventTarget.prototype.addEventListener._original=e}});";
	
    	$handle = 'om-remove-passive-listener-warning-js';
    
    	$tag = '
<script id="%s">%s</script>
';
    	
    	printf( $tag, esc_html( $handle ), htmlspecialchars_decode(wp_kses_data($script)) );
    	
    }
    
    public function optimizeWcCartFragments() {
        if ( is_user_logged_in() || ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		global $wp_scripts;

		$fragments_handle = 'wc-cart-fragments';
		$cookie_handle = 'js-cookie';

		if ( isset( $wp_scripts->registered[ $fragments_handle ] ) && $wp_scripts->registered[ $fragments_handle ] && isset( $wp_scripts->registered[ $cookie_handle ] ) && $wp_scripts->registered[ $cookie_handle ] ) {

			$load_fragments_path = esc_url( $wp_scripts->registered[ $fragments_handle ]->src );
			$load_cookie_path = esc_url( $wp_scripts->registered[ $cookie_handle ]->src );

			$wp_scripts->registered[ $fragments_handle ]->src = null;
			$wp_scripts->registered[ $cookie_handle ]->src = null;

			$script = '
				function opm_getCookie(name) {
					var v = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
					return v ? v[2] : null;
				}

				function opm_check_wc_cart_script() {
					var fragments_src = "' . $load_fragments_path . '";
					var cookie_src = "' . $load_cookie_path . '";
					var fragments_id = "opm_loaded_wc_cart_fragments";
					var cookie_id = "opm_loaded_js_cookie";

					if ( document.getElementById( fragments_id ) !== null ) {
						return false;
					}

					if ( opm_getCookie( "woocommerce_cart_hash" ) ) {
						var fragments_script = document.createElement( "script" );
						fragments_script.id = fragments_id;
						fragments_script.src = fragments_src;
						fragments_script.async = true;

						var cookie_script = document.createElement( "script" );
						cookie_script.id = cookie_id;
						cookie_script.src = cookie_src;
						cookie_script.async = true;

						document.head.appendChild( fragments_script );
						document.head.appendChild( cookie_script );
					}
				}

				opm_check_wc_cart_script();
				document.addEventListener( "click", function() { setTimeout( opm_check_wc_cart_script, 1000 ); } );
			';

			// minify the inline script before inject
			/**/
			$script = preg_replace(['/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/','/\>[^\S ]+/s','/[^\S ]+\</s','#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si'],['','>','<','$1$2$3$4$5$6$7'], $script);
			
			// inject the inline script
			wp_add_inline_script( 'jquery',htmlspecialchars_decode(wp_kses_data($script)) );
		}
    }
    
    public function loadBlockLibraryCssConditionally() {
        
        if ( is_admin() || current_user_can('manage_options') || is_user_logged_in() || is_404() ) {
    		return; 
    	}
        
    	wp_dequeue_style ( 'wp-block-library' ); // Remove the combined css first
    	
    	$post = get_post();
    	
    	if ( ! $post ) {
    		return; // Return if there is no post object (new)
    	}
    
    	$post_blocks = parse_blocks( get_post()->post_content ); // Get the blocks used in the current page or post
    	$block_styles = array(); // Define an empty array to store the block styles
    
    	// Get all the registered block types
    	$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
    
    	// Loop through the registered block types and add their corresponding style sheets to the $block_styles array
    	
    	// Skip non-core blocks
    	foreach ( $registered_blocks as $block_name => $block_type ) {
    		if ( strpos( $block_type->name, 'core' ) === false ) {
    			continue;
    		}
    		
    		// Skip these blocks since each of them doesn't have its own dedicated CSS files
    		if ( strpos( $block_type->name, 'shortcode' ) !== false || 
    			strpos( $block_type->name, 'legacy-widget' ) !== false ||
    			strpos( $block_type->name, 'widget-group' ) !== false ||
    			strpos( $block_type->name, 'block' ) !== false ||
    			strpos( $block_type->name, 'comment-author-name' ) !== false ||
    			strpos( $block_type->name, 'comment-date' ) !== false ||
    			strpos( $block_type->name, 'comment-edit-link' ) !== false ||
    			strpos( $block_type->name, 'comment-reply-link' ) !== false ||
    			strpos( $block_type->name, 'comments-pagination-next' ) !== false ||
    			strpos( $block_type->name, 'comments-pagination-numbers' ) !== false ||
    			strpos( $block_type->name, 'comments-pagination-previous' ) !== false ||
    			strpos( $block_type->name, 'comments-title' ) !== false ||
    			strpos( $block_type->name, 'home-link' ) !== false ||
    			strpos( $block_type->name, 'loginout' ) !== false ||
    			strpos( $block_type->name, 'navigation-submenu' ) !== false ||
    			strpos( $block_type->name, 'pattern' ) !== false ||
    			strpos( $block_type->name, 'post-author-biography' ) !== false ||
    			strpos( $block_type->name, 'post-content' ) !== false ||
    			strpos( $block_type->name, 'post-navigation-link' ) !== false ||
    			strpos( $block_type->name, 'query' ) !== false ||
    			strpos( $block_type->name, 'query-no-results' ) !== false ||
    			strpos( $block_type->name, 'query-pagination-next' ) !== false ||
    			strpos( $block_type->name, 'query-pagination-numbers' ) !== false ||
    			strpos( $block_type->name, 'query-pagination-previous' ) !== false ||
    			strpos( $block_type->name, 'site-tagline' ) !== false ||
    			strpos( $block_type->name, 'site-title' ) !== false ||
    			strpos( $block_type->name, 'social-link' ) !== false ||
    			strpos( $block_type->name, 'template-part' ) !== false ||
    			strpos( $block_type->name, 'term-description' ) !== false ||
    			strpos( $block_type->name, 'column' ) !== false ||
    			strpos( $block_type->name, 'freeform' ) !== false ||
    			strpos( $block_type->name, 'html' ) !== false ||
    			strpos( $block_type->name, 'list-item' ) !== false ||
    			strpos( $block_type->name, 'missing' ) !== false ||
    			strpos( $block_type->name, 'more' ) !== false ||
    			strpos( $block_type->name, 'nextpage' ) !== false ||
    			strpos( $block_type->name, 'post-comments' ) !== false
    		   ) {
    			continue; 
    		}
    
    		$block_style = '/wp-includes/blocks/' . esc_attr( str_replace( 'core/', '', $block_type->name ) ) . '/style.min.css';
    		$block_styles[ $block_name ] = $block_style;
    	}
    
    	// Loop through the blocks used in the page and load the corresponding style sheet for each core Gutenberg block
    	foreach ( $block_styles as $block_name => $block_style ) {
    		if ( has_block( $block_name ) ) {
    			wp_enqueue_style( 'global-styles' );
    			wp_enqueue_style( 'om-wp-block-library-common', '/wp-includes/css/dist/block-library/common.min.css' );
    			wp_enqueue_style( 'om-wp-' . esc_attr( str_replace( 'core/', 'block-', $block_name ) ), esc_url( $block_style ) );
    		}
    	}
    }
    
    public function removeStylesAndScripts() {
		
        if ( is_admin() || current_user_can('manage_options') || is_user_logged_in() ) {
            return;
        }
		
		if ( $this->options['opm_remove_wp_global_styles'] ) {
			wp_dequeue_style( 'global-styles' );
		}
		
		if ( $this->options['opm_remove_wc_blocks_css'] ) {
			$wcBlockstyles = [
				'wc-blocks-style',
				'wc-blocks-vendors-style',
				'wc-blocks-style-all-products',
			];

			foreach ( $wcBlockstyles as $wcBlockstyle ) {
				wp_deregister_style( $wcBlockstyle );
				wp_dequeue_style( $wcBlockstyle );
			}
        }
        
    }
    
    public function removeWpSvgDuotoneFilter() {
        if ( is_admin() || current_user_can('manage_options') || is_user_logged_in() ) {
            return;
        }
        remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
    }
    
    public function removeJqueryMigrate($scripts) {
        if ( is_user_logged_in() ) {
            return;
        }
		if (!is_admin() && isset($scripts->registered['jquery'])) {
	        $script = $scripts->registered['jquery'];
	        
	        if ($script->deps) { // Check whether the script has any dependencies
	            $script->deps = array_diff($script->deps, array(
	                'jquery-migrate'
	            ));
	        }
	    }
    }
    
    public function disableEmojis() {
        
        call_user_func(
    		'remove_action',
    		'wp_head',
    		'print_emoji_detection_script',
    		7
    	);
    
    	call_user_func(
    		'remove_action',
    		'wp_print_styles',
    		'print_emoji_styles'
    	);
    
    	call_user_func(
    		'remove_action',
    		'admin_print_scripts',
    		'print_emoji_detection_script'
    	);
    
    	call_user_func(
    		'remove_action',
    		'admin_print_styles',
    		'print_emoji_styles'
    	);
    
    	remove_filter('the_content_feed', 'wp_staticize_emoji');
    	remove_filter('comment_text_rss', 'wp_staticize_emoji');
    	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    	add_filter('tiny_mce_plugins', function ($plugins) {
    		if (is_array($plugins)) {
    			return array_diff($plugins, array('wpemoji'));
    		} else {
    			return array();
    		}
    	});
    
    	add_filter('wp_resource_hints', function ($urls, $relation_type) {
    		if ('dns-prefetch' === $relation_type) {
    			$emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
    			$urls = array_diff($urls, array($emoji_svg_url));
    		}
    
    		return $urls;
    	}, 10, 2);
    	
    }
    
    public function disableEmbeds() {
        
        remove_action( 'rest_api_init', 'wp_oembed_register_route' ); // block wp-json/oembed/1.0/
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // Remove oEmbed discovery links.
        remove_action( 'wp_head', 'wp_oembed_add_host_js' ); // Remove oEmbed-specific JavaScript from the front-end and back-end.
        remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 ); // Remove filter of the oEmbed result before any HTTP requests are made.
        
	    add_filter( 'embed_oembed_discover', '__return_false' ); // Turn off oEmbed auto discovery.
        add_filter( 'rewrite_rules_array', [$this,'disable_embeds_rewrites'] ); // Remove all embeds rewrite rules.
        add_filter( 'rest_endpoints', [$this,'disable_embeds_remove_embed_endpoint'] ); // Remove the oembed/1.0/embed REST route.
        add_filter( 'oembed_response_data', [$this,'disable_embeds_filter_oembed_response_data'] );// Disable handling of internal embeds in oembed/1.0/proxy REST route.
        
    }
    
    public function disable_embeds_remove_embed_endpoint( $endpoints ) {
    	unset( $endpoints['/oembed/1.0/embed'] );
    	return $endpoints;
    }
    
    public function disable_embeds_filter_oembed_response_data( $data ) {
    	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
    		return false;
    	}
    	return $data;
    }
    
    public function disable_embeds_rewrites( $rules ) {
    	foreach ( $rules as $rule => $rewrite ) {
    		if ( false !== strpos( $rewrite, 'embed=true' ) ) {
    			unset( $rules[ $rule ] );
    		}
    	}
    	return $rules;
    }
    
    public function usePrefetch() {
        
        if ( is_admin() || current_user_can('manage_options') || is_user_logged_in() || is_404() || function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
    		return; 
    	}
    	
    	$handle = 'om-prefetch-js';
        
        $src = OPTIMIZEMORE_URL . 'prefetch.min.js?ver=' . OPTIMIZEMORE_VERSION;
        
        $script = '<script id="%s" data-type="delay" data-src="%s"></script>
';
        
        printf( $script, esc_html($handle), esc_url($src) );
        
        
        /* inject delay js */
        
        $om_delay_js_time = apply_filters( 'om_delay_js_time', '5e6' );

        $delay_js_script =
            'const loadScriptsTimer=setTimeout(loadScripts,' . esc_attr( $om_delay_js_time ) . ');function triggerScriptLoader(){loadScripts(),clearTimeout(loadScriptsTimer),opmInteractionEvents.forEach(function(e){window.removeEventListener(e,triggerScriptLoader,{passive:!0})})}function loadScripts(){document.querySelectorAll("script[data-type=delay]").forEach(function(e){e.setAttribute("src",e.getAttribute("data-src"))})}opmInteractionEvents=["mouseover","mousemove","mousedown","mouseup","click","keydown","touchstart","touchmove","wheel"],opmInteractionEvents.forEach(function(e){window.addEventListener(e,triggerScriptLoader,{passive:!0})});';
    	   
    	$delay_js_script_handle = 'om-delay-js';
    	
    	$delay_js_inline_script = '<script id="%s">%s</script>' . PHP_EOL;
    	
    	/* inject delay js if opm_delay_js true */
    	
    	if ( $this->options['opm_delay_js'] && !$this->options['opm_delay_js_front_page'] ) {
    	    if ( is_front_page() ) {
    	        printf($delay_js_inline_script, esc_html($delay_js_script_handle), htmlspecialchars_decode(wp_kses_data($delay_js_script)));
    	    }
        }
    	
    	if ( !empty($this->options['opm_custom_pages_id'] )) {
		$om_custom_pages = $this->options['opm_custom_pages_id'];
		$om_custom_pages = explode(",", $om_custom_pages);
		} else { $om_custom_pages = ''; }
		
    	if ( $this->options['opm_delay_js'] && !$this->options['opm_delay_js_pages'] ) {
    	    if ( is_page( $om_custom_pages ) && !is_front_page() ) {
    	        printf($delay_js_inline_script, esc_html($delay_js_script_handle), htmlspecialchars_decode(wp_kses_data($delay_js_script)));
    	    }
        }
        
        if ( $this->options['opm_delay_js'] && !$this->options['opm_delay_js_archives'] ) {
    	    if ( is_home() || is_archive() && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    	        printf($delay_js_inline_script, esc_html($delay_js_script_handle), htmlspecialchars_decode(wp_kses_data($delay_js_script)));
    	    }
        }
        
        if ( $this->options['opm_delay_js'] && !$this->options['opm_delay_js_singular'] ) {
    	    if ( is_singular() && !is_page()  && (!function_exists('is_woocommerce') || !is_woocommerce()) ) {
    	        printf($delay_js_inline_script, esc_html($delay_js_script_handle), htmlspecialchars_decode(wp_kses_data($delay_js_script)));
    	    }
        }
        
        if ( $this->options['opm_delay_js'] && !$this->options['opm_delay_js_wc_product'] ) {
    	    if ( function_exists('is_woocommerce') && is_product() ) {
    	        printf($delay_js_inline_script, esc_html($delay_js_script_handle), htmlspecialchars_decode(wp_kses_data($delay_js_script)));
    	    }
        }
        
        if ( $this->options['opm_delay_js'] && !$this->options['opm_delay_js_wc_archives'] ) {
    	    if ( ( function_exists('is_woocommerce') && is_shop() ) || ( function_exists('is_woocommerce') && is_product_category() ) ) {
    	        printf($delay_js_inline_script, esc_html($delay_js_script_handle), htmlspecialchars_decode(wp_kses_data($delay_js_script)));
    	    }
        }
        
        /* inject delay js if opm_delay_js false */
        
        if ( $this->options['opm_delay_js'] ) {
            return;
        }
            
        printf($delay_js_inline_script, esc_html($delay_js_script_handle), htmlspecialchars_decode(wp_kses_data($delay_js_script)));
        
    }
    
    public function combineGoogleFontsInit() {
        
        if (!is_admin() ) {
            // Start the output buffer
            ob_start(array($this, 'combineGoogleFonts'));
            // Register a shutdown function to flush the buffer and process the modified content
            register_shutdown_function(array($this, 'endBuffer'));
        }
        
    }
    
    private function combineGoogleFonts($buffer) {
        
        if (is_user_logged_in()) {
            return $buffer;
        }
    
        $pattern = '/<link[^>]*href=["\'](https?:\/\/fonts\.googleapis\.com\/css[^"\']*)["\'][^>]*>/iU';
        preg_match_all($pattern, $buffer, $matches);
    
        if (empty($matches[0])) {
            return $buffer;
        }
    
        $fonts_array = [];
        $subsets_array = [];
    
        foreach ($matches[0] as $match) {
            preg_match('/href=["\'](.*?)["\']/', $match, $href_matches);
            $url = isset($href_matches[1]) ? $href_matches[1] : '';
            //$query = wp_parse_url(html_entity_decode($url), PHP_URL_QUERY);
            $query = wp_parse_url($url, PHP_URL_QUERY);
    
            if (!empty($query)) {
                $font = wp_parse_args($query);
    
                if (isset($font['family'])) {
                    $font_family = $font['family'];
                    $font_family = rtrim($font_family, '%7C');
                    $font_family = rtrim($font_family, '|');
    
                    if (isset($font['wght'])) {
                        $font_family .= ':' . str_replace(',', ';', $font['wght']);
                    }
    
                    $fonts_array[] = $font_family;
                }
    
                if (isset($font['subset'])) {
                    $subsets_array[] = rawurlencode(htmlentities($font['subset']));
                }
            }
    
            $buffer = str_replace($match, '', $buffer);
        }
    
        if (empty($fonts_array)) {
            return $buffer;
        }
    
        sort($fonts_array);
    
        $font_families = implode('&family=', array_filter(array_unique($fonts_array)));
        $subsets = !empty($subsets_array) ? '&subset=' . implode(',', array_filter(array_unique($subsets_array))) : '';
    
        $new_google_font_url = 'https://fonts.googleapis.com/css2?family=' . $font_families . $subsets;
        
        /*
         * preparation to print the combined google font urls
         */
    
        $new_dns_prefetch_tag = "<link rel='dns-prefetch' href='//fonts.googleapis.com' />";
        $new_google_font_url_tag = PHP_EOL . "<link rel='preconnect' href='https://fonts.gstatic.com'>";
        $new_google_font_url_tag .= PHP_EOL . "<link rel='stylesheet' id='om-combined-google-font-css' href='" . esc_url($new_google_font_url) . "' media='all'>";
        
        $head_tag_pos = stripos($buffer, '</head>');
        $title_tag_pos = stripos($buffer, '</title>');
        $dns_prefetch_tag_pos = stripos($buffer, "<link rel='dns-prefetch' href='//fonts.googleapis.com' />");
        
        /*
         * print the combined google font urls
         */
        
        if ($dns_prefetch_tag_pos === false) {
            if ($title_tag_pos !== false) {
                $buffer = substr_replace($buffer, $new_dns_prefetch_tag . $new_google_font_url_tag, $title_tag_pos + strlen('</title>'), 0);
            } elseif ($head_tag_pos !== false) {
                $buffer = substr_replace($buffer, $new_dns_prefetch_tag . $new_google_font_url_tag, $head_tag_pos, 0);
            } else {
                $buffer = $new_dns_prefetch_tag . $buffer;
            }
        } else {
            if ($head_tag_pos !== false && $dns_prefetch_tag_pos < $head_tag_pos) {
                $buffer = substr_replace($buffer, $new_google_font_url_tag, $dns_prefetch_tag_pos + strlen($new_dns_prefetch_tag), 0);
            } else {
                $buffer .= $new_google_font_url_tag;
            }
        }
    
        return $buffer;
    }
    
    public function endBuffer() {
	    // Get the contents of the output buffer
	    $this->buffer = ob_get_contents();
	    // Clean the output buffer if it's active
	    if (ob_get_length() > 0) {
	        ob_end_clean();
	    }
	    // Process the modified content
	    echo $this->buffer;
	    // Flush the output buffer if it's active
	    if (ob_get_length() > 0) {
	        ob_end_flush();
	    }
	}
    
}