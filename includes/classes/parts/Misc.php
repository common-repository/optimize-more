<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class Misc {
    
    private static $_instance;
    private $options = [];
	private $fontCharacters;

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	public function __construct() {
		
	    $this->options = get_option('opm_options', []);
	    
        $this->fontCharacters = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz%20!&"#$%\'()*+,-./:;<=>?@[\\]^_`{|}~%26≤≥±√$€£¥•©®™';
	    
    }

    public function Rewrites($htmlRewrite) {
		
		// google fonts
		foreach ($htmlRewrite->find("link[rel=stylesheet][href*=fonts.googleapis.com],link[rel=stylesheet][href*=api.fonts.coollabs.io]") as $google_fonts) {
		    
            // control font display
            if ($this->options['opm_font_display'] !== '0') {
                
                $font_display = '';
        
                switch ($this->options['opm_font_display']) {
                    case 'block':
                        $font_display = 'block';
                        break;
                    case 'swap':
                        $font_display = 'swap';
                        break;
                    case 'fallback':
                        $font_display = 'fallback';
                        break;
                    case 'optional':
                        $font_display = 'optional';
                        break;
                    default:
                        $font_display = '';
                }
        
                // Remove font display from href
                $google_fonts->href = preg_replace(
                    ['/&display=swap/', '/&#038;display=swap/'],
                    ['', ''],
                    $google_fonts->href
                );
        
                $google_fonts->href .= '&display=' . $font_display;
            }
            
            // filter google fonts
            if ( $this->options['opm_filter_google_fonts'] ) {
                // Check if the URL already contains the 'text' parameter
                if (strpos($google_fonts->href, '&text') === false) {
                    // Extract font families from the URL
                    preg_match_all('/family=([^&]+)/', $google_fonts->href, $matches);
            
                    // Loop through each font family and append the 'text' parameter
                    foreach ($matches[1] as $fontFamily) {
                        $textParameter = '&text=' . rawurlencode($this->fontCharacters);
                        $google_fonts->href = str_replace("family=$fontFamily", "family=$fontFamily$textParameter", $google_fonts->href);
                    }
            
                }
            }
        
		    
		}

    	
    	return $htmlRewrite;
    }
	
	
	
    
}