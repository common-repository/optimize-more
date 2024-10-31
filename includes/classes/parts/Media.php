<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

/**
 * credits:
 *
 * preload featured images and add no lazy for featured image are forked & modified from Jackson Lewis codes in
 * How to preload images in WordPress
 * https://dev.to/jacksonlewis/how-to-preload-images-in-wordpress-48di
 *
 * specify image dimensions is forked & modified from Fact Maven codes in
 * https://wordpress.org/plugins/specify-image-dimensions/
 *
 * specify svg image dimensions is forked & modified from DahmaniAdame codes in
 * https://github.com/wp-media/wp-rocket/issues/3727#issuecomment-808928527
 */

class Media {
    
    private static $_instance;
    private $options = [];

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	public function __construct() {
		
	    $this->options = get_option('opm_options', []);
	    
    }
    
    private function excludeKeywords($content, $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    public function Rewrites($htmlRewrite) {
		
		/*
		 * preload featured images
		 * 
		 */
		global $post;
		
		// Get the featured image URL for the current post
		$image_size = 'full';
	
	    if ( is_singular( 'product' ) ) {
	        $image_size = 'woocommerce_single';
	
	    } else if ( is_singular( 'post' ) ) {
	        $image_size = 'full';
	    }
		
	    $image_size = apply_filters( 'opm_featured_image_size', $image_size, $post );
		
		$thumbnail_id = '';
		
		if (!empty($post) && is_object($post)) {
			/** Get post thumbnail if an attachment ID isn't specified. */
			$thumbnail_id = apply_filters( 'opm_featured_image_id', get_post_thumbnail_id( $post->ID ), $post );
		}
	
	    /** Get the image */
	    $thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, $image_size );
		
		foreach ($htmlRewrite->find("img[src]") as $image) {
			
			// add 'no-lazy' class to featured image
			if ($thumbnail_id) {
				if ($image->src == $thumbnail_url[0]) {
					$image->class .= ' no-lazy';
				}
			}
			
			// add missing img dimension
			if ( $this->options['opm_add_image_dimensions'] || $this->options['opm_add_svg_image_dimensions'] ) {
				$this->add_img_dimension($image);
			}
            
        }
		
        
        $excludes = apply_filters('opm_exclude_lazy_class', 
            array(
                'no-lazy',
                'skip-lazy',
				'data:image',
                'logo',
            )
		);
		
		// control fetchpriority and loading attributes
		if ( $this->options['opm_lazyload'] ) {
			
			foreach ($htmlRewrite->find("img[src],iframe[src]") as $media_files) {
				// Remove any loading attribute from other code first
				if ($media_files->hasAttribute("loading")) {
					$media_files->removeAttribute("loading");
				}
				// Remove any fetchpriority attribute from other code first
				if ($media_files->hasAttribute("fetchpriority")) {
					$media_files->removeAttribute("fetchpriority");
				}
				// Set fetchpriority and loading attribute based on excluded keywords
				if ($this->excludeKeywords($media_files, $excludes)) {
					$media_files->setAttribute("loading", "eager");
					$media_files->setAttribute("fetchpriority", "high");
				} else {
					$media_files->setAttribute("loading", "lazy");
					$media_files->setAttribute("fetchpriority", "low");
				}
			}
			
		}
		
		// preload fetured images
		if ( $this->options['opm_preload_featured_image'] ) {
			if ($thumbnail_id) {
				$preload_img_tags = PHP_EOL . "<link rel='preload' as='image' href='". esc_url( $thumbnail_url[0] ) . "'>";
				$htmlRewrite->find('title', 0)->outertext .= $preload_img_tags;	
			}
		}
    	
    	return $htmlRewrite;
    }
	
	public function add_img_dimension($image) {
		// Match all image attributes
		$attributes = 'src|srcset|longdesc|alt|class|id|usemap|align|border|hspace|vspace|crossorigin|ismap|sizes|width|height';
		// Extract attribute values
		preg_match_all('/(' . $attributes . ')=("[^"]*")/i', $image->outertext, $img);
		// If no 'width' or 'height' is available or blank, calculate dimensions
		if (!in_array('width', $img[1]) || !in_array('height', $img[1]) || (in_array('width', $img[1]) && in_array('""', $img[2])) || (in_array('height', $img[1]) && in_array('""', $img[2]))) {
			$src = $img[2][array_search('src', $img[1])];

			// If image is an SVG
			if ($this->options['opm_add_svg_image_dimensions']) {
				if (preg_match('/(.*).svg/i', $src)) {
					$svgDimensions = $this->getSvgDimensionsFromCache($image, $src);
					if ($svgDimensions === false) {
						// SVG dimensions not in cache, calculate and store in cache
						$svgDimensions = $this->calculateAndCacheSvgDimensions($image, $src);
					}
				}
			}
			
			if ($this->options['opm_add_image_dimensions']) {
				if (! preg_match('/(.*).svg/i', $src)) {
					$this->setOtherImageDimensions($image, $src);
				}
			}
			
		}
	}
	
	private function setOtherImageDimensions($image, $src) {
		// Get accurate width and height attributes
		list($width, $height) = getimagesize(str_replace("\"", "", $src));
		// Set dimensions
		$image->width = $width;
		$image->height = $height;
	}
	
	private function getSvgDimensionsFromCache($image, $src) {
		// Use the SVG URL as the cache key
		$cacheKey = 'svg_dimensions_' . md5($image, $src);
		return get_transient($cacheKey);
	}
	
	private function calculateAndCacheSvgDimensions($image, $src) {
		$svgDimensions = $this->setSvgDimensions($image, $src);
		// Cache SVG dimensions for 1 day
		$cacheKey = 'svg_dimensions_' . md5($src);
		set_transient($cacheKey, $svgDimensions, DAY_IN_SECONDS);
		return $svgDimensions;
	}
	
	private function setSvgDimensions($image, $src) {
		$image->width = null;
		$image->height = null;
		// Calculate width and height using svg_getimagesize
		$size = $this->svg_getimagesize(str_replace("\"", "", $src));
		// Set dimensions
		$image->width = $size[0];
		$image->height = $size[1];
	}
    
    private function svg_getimagesize($image) {
		
		$file = new \SplFileInfo($image);
		$ext = $file->getExtension();

		// Initialize size array
		$size = array();

		// Filter SVG files and extract the dimensions using DOMDocument
		if( $ext === 'svg' ){
			$svgfile = simplexml_load_file(rawurlencode($image));
			$width = explode(' ', (string)$svgfile->attributes()->width);
			$height = explode(' ', (string)$svgfile->attributes()->height);

			// Use width and height attributes if present
			if(!empty($width[0]) && !empty($height[0])) {
				$size[] = $width[0]; // adding width
				$size[] = $height[0]; // adding height
				$size[] = 0; // adding image type - 0 for SVG since there is no IMAGETYPE_SVG constant
				$size[] = 'width="'.$width[0].'" height="'.$height[0].'"'; // adding the dimension on the array
				return $size;
			}

			// Fallback to viewBox if one or both width and height attributes are missing from the SVG document
			$viewBox = explode(' ', (string)$svgfile->attributes()->viewBox);
			if (!empty ($viewBox)) {
				// width and height are respectively the last two entries in the viewBox tag
				$size = array_splice($viewBox, -2);
				// Making the $size array fit the getimagesize() format
				$size[] = 0; // adding image type - 0 for SVG since there is no IMAGETYPE_SVG constant
				$size[] = 'width="'.$size[0].'" height="'.$size[1].'"'; // adding the dimension on the array
				return $size;
			}
		  }

		return $size;
	}
	
    
}