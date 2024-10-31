=== Optimize More! ===
Contributors: aryadhiratara
Tags: javascript, css, js, optimize, core web vitals, pagespeed, performance, speed, delay, async, defer javascript, delay css, delay javascript, inline, inline css, inline javascript, google font, google fonts, lazy load, loading lazy, varvy defer js,
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 2.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A DIY WordPress Page Speed Optimization Pack. Optimize CSS & JavaScripts Delivery: Load CSS Asynchronously, Delay CSS & JavaScripts until User Interaction, Remove Unused CSS & JavaScripts Files, Preload Fonts, Critical CSS & JavaScripts, Inline CSS & JS, Defer JavaScripts, and more.

== Description ==

**A 'Do It Yourself' WordPress Page Speed Optimization Pack.**

_Optimizing web pages is really just about controlling how assets are delivered._

**Optimize your site further. Load faster on GTmetrix, Get better scores on Google Page Speed Insight.**

Control your CSS & JavaScripts Delivery: Load CSS Asynchronously, Delay CSS & JavaScript until User Interaction, Remove Unused CSS & JavaScript Files, Preload Critical CSS & JavaScript, Defer JavaScripts, and more.

You can choose each CSS & JavaScripts individually and what type of execution is required per each post types.

This plugin inspired by the mighty [Flying Scripts](https://wordpress.org/plugins/flying-scripts/) from Gijo Varghese. Using HTML Dom Parser to manipulate your page's HTML, so we can simply use the keyword of the assets to target them.

## Main Features

- **Load CSS Asynchronously** — load any CSS file(s) asynchronously on selected post/page types.

- **Delay CSS and JavaScripts until User Interaction** — delay any CSS/JavaScripts load until user interaction on selected post/page types. 
_Note_:
  - _By default, the delay JavaScripts and CSS features are configured to user interaction based. You can change that using filter._
  - _If you prefer to use 'exclusions', you can simply delay all JavaScripts using "`.js`" keywords, and use provided filter to exclude specific JavaScripts._

- **Defer JavaScripts** — defer loading any JavaScript file(s) on selected post/page types.

- **Advance Defer JavaScripts** — hold any JavaScript file(s) load until everything else has been loaded. Adapted from the legendary **varvy's defer JavaScripts** method _*recommended for defer loading 3rd party scripts like ads, pixels, and trackers_

- **Preload Critical CSS, JavaScripts, and Font Files** — preload any critical CSS/JavaScript/Font file(s) on selected post/page types.

- **Inline CSS and JavaScript Files** — inline any critical CSS/JavaScript file(s) on selected post/page types. _*warning: Inlining multiple / large files might slow down your site's performance. Enabling this without using a caching system is generally not recommended._

- **Remove Unused CSS and JavaScripts Files** — remove any unused CSS/JavaScripts file(s) on selected post/page types.

**Use case**:

- Have CSS files that are only be used in the below the fold area? Delay them.
- Have JavaScripts files that are not required in initial page rendering? Delay them.
- Have CSS files that are used in the above the fold area? Preload them.
- Have JavaScripts files such as jQuery that are needed to be load since the beginning? Preload them.
- Have CSS files that are critical for above the fold area? Inline them.
- Need to remove "Eliminate Render Blocking Resources" warnings on Google Page Speed Insights? Async, Defer, Inline, or Preload them.

## Other Features

- **Load Gutenberg CSS conditionally** — This feature will make the combined CSS (wp-block-library) removed and your page **will only load the CSS of each block** that you use on the page (and only if you use one of the core blocks!). Each CSS of the core blocks will only get enqueued when the block gets rendered on a page. This will save you from worrying if you still need to use the native Gutenberg core blocks in some of your pages.
_Note: the inline "global-styles" will also only load if you use one of the core blocks when you combine this feature with the **Remove FSE Global Styles** feature_.

- **Remove FSE Global Styles** — Remove WP "global-styles-inline-css". _Note: this will still conditionally loaded if you enable **Load Gutenberg CSS conditionally** feature_.

- **Remove SVG Duotone Filter**.

- **Filter Google Fonts** — Filter the Google Font Characters to use most common characters only. In most font families, this will significantly reduce the font file size.  _*remember to use the web-safe font that is most similar to the font you are using as the font family fallback or **you can simply modify the default character using the provided filter**_.

- **Combined Google Fonts** — Combine google fonts css into one single line _*only works for google font url(s) that are using the latest Google Font API (css2)_.

- **Select Font Display** — Choose the best google fonts' font-display strategy for your website.

- **Remove Passive Listener Warnings** — Remove the "Does not use passive listeners to improve scrolling performance" warning on Google PageSpeed Insights

- **Prefetch Pages** — Prefetch in view links so visitors can switch pages (_more_) instantly. _*based on Gijo's [Flying Pages](https://wordpress.org/plugins/flying-pages/)_

- **Optimize WC Cart Fragments** — Disable WC Cart Fragments, only when it's empty. _*based on Optimocha's (Barış Ünver) [Disable Cart Fragments](https://wordpress.org/plugins/disable-cart-fragments/)_

- **Remove WooCommerce Blocks CSS**.

- **Disable jQuery Migrate**.

- **Disable WP Embeds**.

- **Disable WP Emojis**.

- **Lazyload, Preload, and More** — Add Lazyload, Preload, and More features. This is a non JavaScript version of [Lazyload, preload, and more](https://wordpress.org/plugins/lazyload-preload-and-more/). *lazyload applied for images/iframes tag only. if you need to lazyload css background images, use the [Lazyload, preload, and more](https://wordpress.org/plugins/lazyload-preload-and-more/) plugin instead.
 - Control your images and iframes delivery
 - Automatically preload featured images
 - Add images dimensions (also able to add SVG images dimensions)

&nbsp;
A simple tutorial of how to use this plugin: [How to use Optmize More Plugin](https://dhiratara.me/how-to/use-optimize-more/) (Indonesian Version: [Cara mempercepat loading WordPress dengan plugin Optimize More](https://thinkdigital.co.id/wordpress/plugin/optimize-more/)).

Big thanks to Gijo Varghese, without his codes in Flying Scripts, I can never be able to build this plugin. Thanks Gijo! :)

## Credits

- **Gijo Varghese** for his codes in Flying Scripts and Flying Pages
- **Barış Ünver** for his codes in Disable Cart Fragments

## Disclaimer

- This plugin should works well with any caching plugins.

- This plugin only adds 1 extra row to your database. And it will self delete upon uninstallation.

- I built this plugin to optimize my Clients' site. And I can get a very good scores even before activating my caching plugin.


## Available Filters

### To change the delay configuration:

By default, the delay JavaScripts and CSS are configured to user interaction based. But you can change that using filter:

_For delay CSS, e.g.:_

    add_filter( 'om_delay_css_time', function($om_delay_css_time) {
		return '3*1000';
	} );


_For delay JavaScripts, e.g.:_

    add_filter( 'om_delay_css_time', function($om_delay_css_time) {
		return '3*1000';
	} );



or if you want to change it for specific page only:

_For delay CSS, e.g.:_

	add_filter( 'om_delay_css_time', function($om_delay_css_time) {
		if (is_front_page()) {
			return '3*1000';
		}
		else {
			return $om_delay_css_time;
		}
	} );


_For delay JavaScripts e.g.:_

	add_filter( 'om_delay_js_time', function($om_delay_js_time) {
		if (is_front_page()) {
			return '3*1000';
		}
		else {
			return $om_delay_js_time;
		}
	} );


### To use Exclusions instead of Inclusions

If you prefer to use 'exclusions' method, simply delay all JavaScript files using "`.js`" keyword, and use the provided filters to exclude specific JavaScript files:

Example filter to add exclude list for the whole site (general):

	add_filter('delay_js_exclude_list_default', function($exclusion_list) {
		$exclusion_list[] = 'jquery-core';
		$exclusion_list[] = 'js-cookie';
		$exclusion_list[] = 'wc-add-to-cart-js';
		return $exclusion_list;
	});

Example filter to add exclude list for Homepage option:

	add_filter('delay_js_exclude_list_front_page', function($exclusion_list) {
		$exclusion_list[] = 'jquery';
		$exclusion_list[] = 'custom-js';
		return $exclusion_list;
	});


Example filter to add exclude list for Custom Page option:

	add_filter('delay_js_exclude_list_pages', function($exclusion_list) {
		$exclusion_list[] = 'jquery';
		$exclusion_list[] = 'another-js';
		return $exclusion_list;
	});


Example filter to add exclude list for Archives option:

	add_filter('delay_js_exclude_list_archives', function($exclusion_list) {
		$exclusion_list[] = 'jquery';
		$exclusion_list[] = 'another-js';
		return $exclusion_list;
	});


Example filter to add exclude list for Singular option:

	add_filter('delay_js_exclude_list_singular', function($exclusion_list) {
		$exclusion_list[] = 'jquery';
		$exclusion_list[] = 'another-js';
		return $exclusion_list;
	});


Example filter to add exclude list for WooCommerce Products option:

	add_filter('delay_js_exclude_list_wc_product', function($exclusion_list) {
		$exclusion_list[] = 'jquery';
		$exclusion_list[] = 'another-js';
		return $exclusion_list;
	});


Example filter to add exclude list for WooCommerce Archives option:

	add_filter('delay_js_exclude_list_wc_archives', function($exclusion_list) {
		$exclusion_list[] = 'jquery';
		$exclusion_list[] = 'another-js';
		return $exclusion_list;
	});


### To modify the Filter Google Font Characters

By default, this feature filtered the Google Font Characters to only use:

	1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz%20!&"#$%\'()*+,-./:;<=>?@[\\]^_`{|}~%26≤≥±√$€£¥•©®™


You can modify the characters using this filter:

Example filter to add some extra characters:

	add_filter('font_characters', function ($fontCharacters) {
		// Modify the characters as needed
		$fontCharacters .= 'éèêñ';
		return $fontCharacters;
	});


Example filter to fully use your preferred characters:

	add_filter('font_characters', function ($fontCharacters) {
		// Modify the characters as needed
		$fontCharacters = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz%20!"#$%\'()*+,-./:;';
		return $fontCharacters;
	});

_*note the 'dot' difference_

### Lazy Load, Preload, and more filters

Example filter to add extra lazyload exclude list:

    add_filter( 'opm_exclude_lazy_class', function($opm_exclude_lazy_class) {
        return array( 
			'my-logo', 'my-hero-img', 'exclude-lazy'
        );
    } );
	

Example filter to change the image sizes for preload featured image:

    add_filter( 'opm_featured_image_size', function($image_size, $post) {
        if ( is_singular( 'post' ) ) { return 'large'; }
        elseif ( is_singular( 'product' ) ) { return 'full'; }
        else { return $image_size; }
    }, 10, 2 );


&nbsp;
## Need Help?

Still having trouble to speed up your site and need someone to do that for you? Try my **[WordPress Speed Optimization Service](https://dhiratara.me/services/speed-optimization/)**.

&nbsp;
## Check out my other plugins:

- **[Optimize More! Images](https://wordpress.org/plugins/optimize-more-images/)**
A simple yet powerfull image, iframe, and video optimization plugin.
- **[Lazyload, Preload, and more!](https://wordpress.org/plugins/lazyload-preload-and-more/)**
A simplified version of **Optimize More! Images**. This tiny little plugin (around 14kb zipped) is able to do what **Optimize More! Images** can do but without UI for settings (you can customize the default settings using filters).
- **[Animate on Scroll](https://wordpress.org/plugins/animate-on-scroll/)**
Animate any Elements on scroll using the popular AOS JS library simply by adding class names.
- **[Shop Extra - WooCommerce Extras](https://wordpress.org/plugins/shop-extra/)**
A lightweight plugin to enhance your WooCommerce & Business site. Floating WhatsApp Chat Widget, WhatsApp Order Button for WooCommerce, Hide/Disable WooCommerce Elements, WooCommerce Strings Translations, add Extra Product Tabs, add Date Picker to products, limit order quantity, add Custom Option to Checkout Page, Add Edit Order features to Checkout page, and many more.
- **[Image & Video Lightbox](https://wordpress.org/plugins/image-video-lightbox/)**

&nbsp;
== Frequently Asked Questions ==

= How to target the CSS/JavaScripts file(s)? =

Use keywords: this can be the CSS ID, filename, or even the folder name (/folder-name/) if you want to target all CSS from some specific plugin.


== Installation ==

#### From within WordPress

1. Visit `Plugins > Add New`
1. Search for `Optimize More` or `Arya Dhiratara`
1. Activate Optimize More from your Plugins page
1. Find Optimize More in your sidebar menu to configure settings


#### Manually

1. Download the plugin using the download link in this WordPress plugins repository
1. Upload `optimize-more` folder to your `/wp-content/plugins/` directory
1. Activate Optimize More plugin from your Plugins page
1. Find Optimize More in your sidebar menu to configure settings


== Screenshots ==

1. Async / Defer Tab — Load CSS files asynchronously and Defer JavaScript files
2. Delay Tab — Delay CSS and JavaScript files until User Interactions
3. Preload Tab — Preload CSS, JavaScript, and Font files
4. Remove Tab — Remove (unused) CSS and JavaScript fies
5. Inline Tab — Inline CSS and JavaScript fies
6. Miscellaneous Tab

== Changelog ==


= 2.0.3 =

- Fix can't enter (add new line) inside the text area in some browser

= 2.0.2 =

- Change "Add display=swap" feature to "Select Font Display Options". You can now choose the best font-display strategy for your website.
- Refactoring "Filter Google Fonts" feature. Hopefully, this will solve any issues with google fonts url that have multiple font families.
- [new] Add Lazyload, Preload, and More features. This is a non JavaScript version of [Lazyload, preload, and more](https://wordpress.org/plugins/lazyload-preload-and-more/).
 - Control your images and iframes delivery
 - Automatically preload featured images
 - Add images dimensions (also able to add SVG images dimensions)

= 2.0.1 =

- Exclude "data:image" from minification in "Inline CSS" minifier function.

= 2.0.0 =

Complete refactor of the plugin's code.
Bump to version 2.0 to mark the refactored version of this plugin and future debugging.

Reasons:

- To align the structure and features with the full version of this plugin (which I use to optimize my clients' sites) to make both plugins easier to maintain. This WP repo version now has 99% of the features of the full version.
- Change the conditional tags to simplified the logic and for better grouping. (for example, "Shop" and "Product Category Page" are now merged in "WooCommerce Archives". Non-WooCommerce Archives now can be targeted by "Archives" fields. "Single Post" is now changed to "Singular" and targets all singular except pages to make the plugin able to target single custom post type.
- Change and simplified the options name for better consistency.
 _note, with this changes:_
  - _existing users will get_ "undefined some_options ...." _warning if you set WP_DEBUG to true in wp-config. it's normal, and will be gone when new settings saved._
  - _make sure to make a notes of your previous settings._
- Use _namespace_ in order to be more aligned with WordPress coding standard

With these changes, for existing users, it would be best to treat it as a fresh installation.

= 1.1.1 =
- Fix undefined variable error in wc product category codes

= 1.1.0 =
- Add **Remove Passive Listener Warnings** feature - Remove the "Does not use passive listeners to improve scrolling performance" warning on Google PageSpeed Insights

= 1.0.9 =

- Add **Advance Defer JavaScripts** feature - hold JavaScripts load until everything else has been loaded. Adapted from the legendary **varvy's defer JavaScripts** method _*recommended for defer loading 3rd party scripts like ads, pixels, and trackers_
- Add **Defer JavaScripts** feature - selectively defer loading JavaScript file(s) on selected post/page types.

= 1.0.8 =

- Fix some minor bugs in the new Load each core blocks CSS conditionally feature.

= 1.0.7 =

- **New!** Added a new function for the Remove Gutenberg CSS feature (in the extra tab). This new function will make the combined CSS removed and your page will only load the CSS of each block you use on the page (and only if you use one of the core blocks!). So the description now changes to "**Load each core blocks CSS conditionally**".
This will save you from worrying if you still need to use the native Gutenberg core blocks in some of your pages. Just like the previous function, the CSS will not load at all if you don't use any of the core blocks in your pages. 
If there's any of the core blocks used, this will also load a small 'wp-includes/css/dist/block-library/common.css' file, which contains generic styles like the default colors definitions, basic styles for text alignments, and styles for the .screen-reader-text class.

- Add extra information to the plugin description about my other plugins.

= 1.0.6 =

- Fix undefined variable warning on PHP 8.2, as reported by Nate (@goseongguy). Thank you!

= 1.0.5 =

- Added extra conditional tags for **Custom Pages** so it also targets the blog archive, per @sermalefico's request

= 1.0.4 =

- Compatibility check with WordPress 6.1.1
- Exclude WooCommerce `My Account` page from delay JavaScripts and delay css feature
- Change plugin banner

= 1.0.3 =

- Bump version to 1.0.3 to push 1.0.2 updates

= 1.0.2 =

- Prevent custom pages targeting from executing anything on WooCommerce Cart, Checkout, and other WooCommerce Endpoint pages

= 1.0.1 =

- Some changes

= 1.0.0 =

- Initial release