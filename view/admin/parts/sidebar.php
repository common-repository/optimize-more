<div class="opm-body-header">
    <h2>.: Helpers :.</h2>
</div>

<ul class="list-sq pt-10 pb-24 mb-10">
   
    <li>Use keywords: One per line. This can be the CSS/JS ID, filename, or even the folder name if you want to target all CSS/JS from some specific plugin.</li>
	<li class=" ">What's targeted by:<br>
		<ul class="list-sq mt-5">
			<li class="mb-0"><strong>Homepage?</strong><br>
				<code class="scroll">if ( is_front_page() )</code>
			</li>
			<li class="mb-0"><strong>Custom Pages?</strong><br>
				<code class="scroll">if ( is_page() && !is_front_page() )</code>
			</li>
			<li class="mb-0"><strong>Archives?</strong><br>
				<code class="scroll">if ( is_home() || is_archive() && !is_woocommerce() )</code>
			</li>
			<li class="mb-0"><strong>Singular?</strong><br>
				<code class="scroll">if ( is_singular() && !is_page()  && !is_woocommerce()) )</code>
			</li>
			<?php if( class_exists( 'WooCommerce' ) ): ?> <!-- check if WooCommerce plugin active -->
			<li class="mb-0"><strong>WooCommerce Product?</strong><br>
				<code class="scroll">if ( is_product() )</code>
			</li>
			<li class="mb-0"><strong>WooCommerce Archives?</strong><br>
				<code class="scroll">if ( is_shop() || is_product_category() )</code>
			</li>
			<?php endif; ?> <!-- end checking if WooCommerce plugin active -->
		</ul>
	</li>
    <li>By default, the <strong>Custom Pages</strong> is targeting all regular pages except Homepage. You can put specific page ID in the extra tab if you want it to target specific pages only.</li>
    <li>By default, delay JavaScripts and CSS are configured to user interaction based.<br>
    Use <code>om_delay_css_time</code> and <code>om_delay_js_time</code> filter if you want to change the delay JS and CSS execution based on time.
    e.g.:<br>
    <span class="code">add_filter( 'om_delay_css_time', function($om_delay_css_time) {
      return '2*1000';
    } );</span>
    <span class="code">add_filter( 'om_delay_js_time', function($om_delay_js_time) {
        return '3*1000';
    } );</span>
    </li>
    <li>Learn how to fully utilize this plugin in this <a href="https://dhiratara.me/how-to/optimize-wordpress-using-optimize-more-plugin/" target="_blank" rel="noopener">article</a>.</li>
    
</ul>


<div class="brand mt-auto pt-18" style="text-align:right">
	by <a target="_blank" href="https://dhiratara.me/" title="Eager to help you creating a fast loading WordPress site">Arya Dhiratara</a>
</div>