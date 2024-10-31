<?php

namespace OptimizeMore;

include_once(OPTIMIZEMORE_LIBRARY_DIR . 'ori-dom-parser.php');

if (!defined('WPINC')) { die; }

class Parser {
    
    private $options = [];
    
    private $AsyncCSS;
    private $DeferJS;
    private $AdvDeferJS;
    private $DelayCSS;
    private $DelayJS;
    private $PreloadCSS;
    private $PreloadJS;
    private $PreloadFont;
    private $RemoveCSS;
    private $RemoveJS;
    private $InlineCSS;
    private $InlineJS;
    private $Media;
    private $Misc;
    private $DelayContent;
    
    private $buffer;

    public function __construct() {
        
        $this->options = get_option('opm_options', []);

        if ( $this->options['opm_async_css'] ) {
            $async_css_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/AsyncCss.php';
            if ( file_exists( $async_css_file ) ) {
                require_once( $async_css_file );
                $this->AsyncCSS = AsyncCSS::getInstance();
            }
        }
        
        if ( $this->options['opm_defer_js'] ) {
            $defer_js_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/DeferJs.php';
            if ( file_exists( $defer_js_file ) ) {
                require_once( $defer_js_file );
                $this->DeferJS = DeferJS::getInstance();
            }
        }
        
        if ( $this->options['opm_adv_defer_js'] ) {
            $adv_defer_js_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/AdvDeferJs.php';
            if ( file_exists( $adv_defer_js_file ) ) {
                require_once( $adv_defer_js_file );
                $this->AdvDeferJS = AdvDeferJS::getInstance();
            }
        }
        
        if ( $this->options['opm_preload_js'] ) {
            $preload_js_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/PreloadJs.php';
            if ( file_exists( $preload_js_file ) ) {
                require_once( $preload_js_file );
                $this->PreloadJS = PreloadJS::getInstance();
            }
        }
        
        if ( $this->options['opm_delay_js'] ) {
            $delay_js_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/DelayJs.php';
            if ( file_exists( $delay_js_file ) ) {
                require_once( $delay_js_file );
                $this->DelayJS = DelayJS::getInstance();
            }
        }
        
        if ( $this->options['opm_delay_css'] ) {
            $delay_css_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/DelayCss.php';
            if ( file_exists( $delay_css_file ) ) {
                require_once( $delay_css_file );
                $this->DelayCSS = DelayCSS::getInstance();
            }
        }
        
        if ( $this->options['opm_remove_css'] ) {
            $remove_css_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/RemoveCss.php';
            if ( file_exists( $remove_css_file ) ) {
                require_once( $remove_css_file );
                $this->RemoveCSS = RemoveCSS::getInstance();
            }
        }
        
        if ( $this->options['opm_remove_js'] ) {
            $remove_js_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/RemoveJs.php';
            if ( file_exists( $remove_js_file ) ) {
                require_once( $remove_js_file );
                $this->RemoveJS = RemoveJS::getInstance();
            }
        }
        
        if ( $this->options['opm_preload_css'] ) {
            $preload_css_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/PreloadCss.php';
            if ( file_exists( $preload_css_file ) ) {
                require_once( $preload_css_file );
                $this->PreloadCSS = PreloadCSS::getInstance();
            }
        }
        
        if ( $this->options['opm_preload_font'] ) {
            $preload_font_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/PreloadFont.php';
            if ( file_exists( $preload_font_file ) ) {
                require_once( $preload_font_file );
                $this->PreloadFont = PreloadFont::getInstance();
            }
        }
        
        if ( $this->options['opm_inline_css'] ) {
            $inline_css_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/InlineCss.php';
            if ( file_exists( $inline_css_file ) ) {
                require_once( $inline_css_file );
                $this->InlineCSS = InlineCSS::getInstance();
            }
        }
        
        if ( $this->options['opm_inline_js'] ) {
            $inline_js_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/InlineJs.php';
            if ( file_exists( $inline_js_file ) ) {
                require_once( $inline_js_file );
                $this->InlineJS = InlineJS::getInstance();
            }
        }
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
		// Check if OPM EXTRA is active.
        $this->is_opm_extra_active = is_plugin_active('optimize-more-extra/dhiratara.php');
        
         // run delay content file
        if ( $this->options['opm_delay_content'] ) {
			if ($this->is_opm_extra_active) {
				$delay_content_file = OPTIMIZEMORE_EXTRA_DIR . '/includes/inc/DelayContent.php';
				if ( file_exists( $delay_content_file ) ) {
					require_once( $delay_content_file );
					$this->DelayContent = DelayContent::getInstance();
				}
			}
        }
        
		if ( $this->options['opm_control_media_files'] ) {
			$media_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/Media.php';
				if ( file_exists( $media_file ) ) {
					require_once( $media_file );
					$this->Media = Media::getInstance();
            }
		}
		
		$misc_file = OPTIMIZEMORE_CLASSES_DIR . 'parts/Misc.php';
		if ( file_exists( $misc_file ) ) {
			require_once( $misc_file );
			$this->Misc = Misc::getInstance();
        }
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
    
    public function htmlRewrite($html) {
        
        try {
            // Process only GET requests
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                return $html;
            }

            // check empty
            if (!isset($html) || trim($html) === '') {
                return $html;
            }

            // return if content is XML
            if (strcasecmp(substr($html, 0, 5), '<?xml') === 0) {
                return $html;
            }

            // Check if the code is HTML, otherwise return
            if (trim($html)[0] !== "<") {
                return $html;
            }
            
            // return for logged-in users
            if ( \is_user_logged_in()) {
                return $html;
            }
            
            // Parse HTML
            $htmlRewrite = str_get_html($html);

            // Not HTML, return original
            if (!is_object($htmlRewrite)) {
                return $html;
            }
            
            // Call the features' rewrite functions
            $this->Misc->Rewrites($htmlRewrite); // the condiional is directly inside the class
            
            if ( $this->options['opm_remove_css'] ) {
                $this->RemoveCSS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_remove_js'] ) {
                $this->RemoveJS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_preload_js'] ) {
                $this->PreloadJS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_async_css'] ) {
                $this->AsyncCSS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_delay_js'] ) {
                $this->DelayJS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_delay_css'] ) {
                $this->DelayCSS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_adv_defer_js'] ) {
                $this->AdvDeferJS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_defer_js'] ) {
                $this->DeferJS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_preload_css'] ) {
                $this->PreloadCSS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_preload_font'] ) {
                $this->PreloadFont->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_inline_css'] ) {
                $this->InlineCSS->Rewrites($htmlRewrite);
            }
            if ( $this->options['opm_inline_js'] ) {
                $this->InlineJS->Rewrites($htmlRewrite);
            }
            
            if ( $this->options['opm_control_media_files'] ) {
                $this->Media->Rewrites($htmlRewrite);
            }
            
            if ( $this->options['opm_delay_content'] ) {
				if ($this->is_opm_extra_active) {
					$this->DelayContent->Rewrites($htmlRewrite);
				}
            }
            

            return $htmlRewrite;

        } catch (Exception $e) {
            return $html;
        }
    }
    
    public function init() {
        if (!is_admin()) {
            // Start the output buffer
            ob_start(array($this, 'htmlRewrite'));
            // Register a shutdown function to flush the buffer and process the modified content
            register_shutdown_function(array($this, 'endHtmlRewrite'));
        }
    }
    
    public function endHtmlRewrite() {
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

$instance = new Parser();
$instance->init();