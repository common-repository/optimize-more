<div class="opm-body-header">
    <h2><?php _e('Site Wide Optimization', 'opm') ?></h2>
</div>

<div class="opm-help flex-full-width pt-15 pb-8 pl-2 mb-10">
	<?php _e('*Applied site wide', 'opm') ?>
</div>

<div class="grid col-3 misc">
    
    <div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_remove_block_library_css" value="1" name="opm_remove_block_library_css" <?php checked(OptimizeMore\opm_field_setting('opm_remove_block_library_css'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_remove_block_library_css"></label><label class="toggle-label" for="opm_remove_block_library_css"><?php _e('Gutenberg Blocks'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Load each core blocks CSS conditionally', 'opm') ?>
        </div>
    </div>
    
    <div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_remove_wp_global_styles" value="1" name="opm_remove_wp_global_styles" <?php checked(OptimizeMore\opm_field_setting('opm_remove_wp_global_styles'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_remove_wp_global_styles"></label><label class="toggle-label" for="opm_remove_wp_global_styles"><?php _e('FSE Global Styles'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Remove WP "global-styles-inline-css"', 'opm') ?>
        </div>
    </div>
    
    <div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_remove_svg_duotone_filter" value="1" name="opm_remove_svg_duotone_filter" <?php checked(OptimizeMore\opm_field_setting('opm_remove_svg_duotone_filter'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_remove_svg_duotone_filter"></label><label class="toggle-label" for="opm_remove_svg_duotone_filter"><?php _e('SVG Duotone Filter'); ?></label> 
        </div>
        <div class="opm-help">	
            <?php _e('Remove WP SVG Duotone Filter', 'opm') ?>
        </div>
    </div>
    
    <?php if( class_exists( 'WooCommerce' ) ): ?> <!-- check if WooCommerce plugin active -->
    <div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_remove_wc_blocks_css" value="1" name="opm_remove_wc_blocks_css" <?php checked(OptimizeMore\opm_field_setting('opm_remove_wc_blocks_css'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_remove_wc_blocks_css"></label><label class="toggle-label" for="opm_remove_wc_blocks_css"><?php _e('WooCommerce Blocks'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Remove WooCommerce blocks css', 'opm') ?>
        </div>
    </div>
    
    <?php endif; ?> <!-- end checking if WooCommerce plugin active -->

    <div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_disable_emoji" value="1" name="opm_disable_emoji" <?php checked(OptimizeMore\opm_field_setting('opm_disable_emoji'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_disable_emoji"></label><label class="toggle-label" for="opm_disable_emoji"><?php _e('Disable Emojis'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Disable WP Emojis', 'opm') ?>
        </div>
    </div>
	
	<div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_disable_embeds" value="1" name="opm_disable_embeds" <?php checked(OptimizeMore\opm_field_setting('opm_disable_embeds'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_disable_embeds"></label><label class="toggle-label" for="opm_disable_embeds"><?php _e('Disable Embeds'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Disable WP Embeds', 'opm') ?>
        </div>
    </div>
	
	<div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_disable_jquery_migrate" value="1" name="opm_disable_jquery_migrate" <?php checked(OptimizeMore\opm_field_setting('opm_disable_jquery_migrate'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_disable_jquery_migrate"></label><label class="toggle-label" for="opm_disable_jquery_migrate"><?php _e('jQuery Migrate'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Remove jQuery migrate', 'opm') ?>
        </div>
    </div>
	
	<div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_filter_google_fonts" value="1" name="opm_filter_google_fonts" <?php checked(OptimizeMore\opm_field_setting('opm_filter_google_fonts'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_filter_google_fonts"></label><label class="toggle-label" for="opm_filter_google_fonts"><?php _e('Filter Google Fonts'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Filter Google Font Characters <span class="custom">*can be modified using filter</span>', 'opm') ?>
        </div>
    </div>
	
	<div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_combine_google_fonts" value="1" name="opm_combine_google_fonts" <?php checked(OptimizeMore\opm_field_setting('opm_combine_google_fonts'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_combine_google_fonts"></label><label class="toggle-label" for="opm_combine_google_fonts"><?php _e('Combine Google Fonts CSS'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('<span class="custom">*only works when using the v2 API (css2) format</span>', 'opm') ?>
        </div>
    </div>
	
	<div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
			<label class="opm-label pt-8 pb-8" for="opm-font-display"><?php _e('Google Font Display'); ?></label> 
            <select id="opm_font_display" name="opm_font_display" class="shop_extra-select w-80">
				<option value="0" <?php selected(OptimizeMore\opm_field_setting('opm_font_display'), 'select', true) ?>>Select</option>
				<option value="block" <?php selected(OptimizeMore\opm_field_setting('opm_font_display'), 'block', true) ?>>Block</option>
				<option value="swap" <?php selected(OptimizeMore\opm_field_setting('opm_font_display'), 'swap', true) ?>>Swap</option>
				<option value="fallback" <?php selected(OptimizeMore\opm_field_setting('opm_font_display'), 'fallback', true) ?>>Fallback</option>
				<option value="optional" <?php selected(OptimizeMore\opm_field_setting('opm_font_display'), 'optional', true) ?>>Optional</option>
			</select>
        </div>
    </div>
	
	<div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_remove_passive_listener" value="1" name="opm_remove_passive_listener" <?php checked(OptimizeMore\opm_field_setting('opm_remove_passive_listener'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_remove_passive_listener"></label><label class="toggle-label" for="opm_remove_passive_listener"><?php _e('Passive Listener Warnings'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Remove the "Does not use passive listeners to improve scrolling performance" warning on PSI', 'opm') ?>
        </div>
    </div>
    
    <?php if( class_exists( 'WooCommerce' ) ): ?> <!-- check if WooCommerce plugin active -->
    
    <div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_wc_cart_fragments" value="1" name="opm_wc_cart_fragments" <?php checked(OptimizeMore\opm_field_setting('opm_wc_cart_fragments'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_wc_cart_fragments"></label><label class="toggle-label" for="opm_wc_cart_fragments"><?php _e('WC Cart Fragments'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Optimize WC Cart Fragments <span class="custom">*based on Optimocha\'s <a target="_blank" href="https://wordpress.org/plugins/disable-cart-fragments/">Disable Cart Fragments</a></span>', 'opm') ?>
        </div>
    </div>
    
    <?php endif; ?> <!-- end checking if WooCommerce plugin active -->
	
	<div class="opm-input-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light" id="opm_use_prefetch" value="1" name="opm_use_prefetch" <?php checked(OptimizeMore\opm_field_setting('opm_use_prefetch'), 1, true) ?> type="checkbox"/>
            <label class="opm-toggle-btn" for="opm_use_prefetch"></label><label class="toggle-label" for="opm_use_prefetch"><?php _e('Prefetch Pages'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('Load page instantly <span class="custom">*based on Gijo\'s <a target="_blank" href="https://wordpress.org/plugins/flying-pages/">FlyingPages</a></span>', 'opm') ?>
        </div>
    </div>
	
	<?php if( class_exists( 'WooCommerce' ) ): ?> <!-- check if WooCommerce plugin active -->
	
    <?php endif; ?> <!-- end checking if WooCommerce plugin active -->

	
</div>

<div class="opm-spacer opm-spacer-large"></div>

<div class="opm-input-group show-hide-group mb-10">
        <div class="opm-input pb-0">
            <input class="opm-toggle opm-toggle-light main-toggle show-hide" data-show-hide="1" id="opm_control_media_files" name="opm_control_media_files" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_control_media_files'), 1, true) ?>/>
            <label class="opm-toggle-btn" for="opm_control_media_files"></label><label class="toggle-label" for="opm_control_media_files"><?php _e('Lazyload, Preload, and More'); ?></label> 
        </div>
        <div class="opm-help">
            <?php _e('A non JavaScript version of <a target="_blank" href="https://wordpress.org/plugins/lazyload-preload-and-more/">Lazyload, preload, and more</a>. <span class="custom">*for image and iframe tags only.<br>if you need to lazyload css background images, use the plugin instead.</span>', 'opm') ?>
        </div>
    
    <div id="" class="show-hide-content mt-15 grid col-2 misc">
		
		<div class="opm-input-group mb-10">
			<div class="opm-input pb-0">
				<input class="opm-toggle opm-toggle-light" id="opm_lazyload" value="1" name="opm_lazyload" <?php checked(OptimizeMore\opm_field_setting('opm_lazyload'), 1, true) ?> type="checkbox"/>
				<label class="opm-toggle-btn" for="opm_lazyload"></label><label class="toggle-label" for="opm_lazyload"><?php _e('Control Lazy Loading Behavior'); ?></label> 
			</div>
			<div class="opm-help">
				<?php _e('Use "no-lazy"/"skip-lazy" to exclude images and iframes from lazy loading. Featured images (including Woo product image) and image with "logo" class/file name will also automatically excluded. This will also add fetchpriority="high" and loading="eager" to all excluded images and iframes. <span class="custom">*you can add your own exclusion using the <code>opm_exclude_lazy_class</code> filter.</span>', 'opm') ?>
			</div>
		</div>
		
		<div class="opm-input-group mb-10">
			<div class="opm-input pb-0">
				<input class="opm-toggle opm-toggle-light" id="opm_preload_featured_image" value="1" name="opm_preload_featured_image" <?php checked(OptimizeMore\opm_field_setting('opm_preload_featured_image'), 1, true) ?> type="checkbox"/>
				<label class="opm-toggle-btn" for="opm_preload_featured_image"></label><label class="toggle-label" for="opm_preload_featured_image"><?php _e('Preload Featured Images'); ?></label> 
			</div>
			<div class="opm-help">
				<?php _e('Automatically preload each of post featured images. This will also preload Woo product image. The default targeted image size is "woocommerce_single" for for Woo product, and "full" for other post types. <span class="custom">*you can change the default image sizes using <code>opm_featured_image_size</code> filter.</span>', 'opm') ?>
			</div>
		</div>
	    
	    <div class="opm-input-group mb-10">
			<div class="opm-input pb-0">
				<input class="opm-toggle opm-toggle-light" id="opm_add_image_dimensions" value="1" name="opm_add_image_dimensions" <?php checked(OptimizeMore\opm_field_setting('opm_add_image_dimensions'), 1, true) ?> type="checkbox"/>
				<label class="opm-toggle-btn" for="opm_add_image_dimensions"></label><label class="toggle-label" for="opm_add_image_dimensions"><?php _e('Add Image Dimension'); ?></label> 
			</div>
			<div class="opm-help">
				<?php _e('Add missing images dimension (width & height attribute) for all image extension except SVGs. Image (tag) which already has width & height value will be skipped.', 'opm') ?>
			</div>
		</div>
	    
	    <div class="opm-input-group mb-10">
			<div class="opm-input pb-0">
				<input class="opm-toggle opm-toggle-light" id="opm_add_svg_image_dimensions" value="1" name="opm_add_svg_image_dimensions" <?php checked(OptimizeMore\opm_field_setting('opm_add_svg_image_dimensions'), 1, true) ?> type="checkbox"/>
				<label class="opm-toggle-btn" for="opm_add_svg_image_dimensions"></label><label class="toggle-label" for="opm_add_svg_image_dimensions"><?php _e('Add SVG Image Dimension'); ?></label> 
			</div>
			<div class="opm-help">
				<?php _e('Seperated on purpose (depends on the file, the process sometimes can add extra load time). <span class="custom">*will try to get the width & height of the SVG document and will use the viewbox as a fallback, so it would be best if you use CSS to style the display if you enable this.</span>', 'opm') ?>
			</div>
		</div>
    
    </div>

</div>