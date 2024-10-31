<div class="opm-body-header">
	<h2><?php _e('Use Extra Purified CSS Files', 'opm') ?></h2>
</div>

<div class="grid col-3 pt-24">
	<!-- start homepage -->
	<div class="opm-input-group toggle-group">
		<input class="opm-toggle opm-toggle-light" id="opm_use_purified_css_front_page" name="opm_use_purified_css_front_page" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_use_purified_css_front_page'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="opm_use_purified_css_front_page"></label>
		<label class="toggle-label" for="opm_use_purified_css_front_page">
		<?php _e('Homepage', 'opm') ?>
		</label>
	</div>
	<!-- end homepage -->
	<!-- start pages -->
	<div class="opm-input-group toggle-group">
		<input class="opm-toggle opm-toggle-light" id="opm_use_purified_css_pages" name="opm_use_purified_css_pages" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_use_purified_css_pages'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="opm_use_purified_css_pages"></label>
		<label class="toggle-label" for="opm_use_purified_css_pages">
		<?php _e('Custom Pages', 'opm') ?>
		</label>
	</div>
	<!-- end pages -->
	<!-- start archives -->
	<div class="opm-input-group toggle-group">
		<input class="opm-toggle opm-toggle-light" id="opm_use_purified_css_archives" name="opm_use_purified_css_archives" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_use_purified_css_archives'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="opm_use_purified_css_archives"></label>
		<label class="toggle-label" for="opm_use_purified_css_archives">
		<?php _e('Archives', 'opm') ?>
		</label>
	</div>
	<!-- end archives -->
	<!-- start singular -->
	<div class="opm-input-group toggle-group">
		<input class="opm-toggle opm-toggle-light" id="opm_use_purified_css_singular" name="opm_use_purified_css_singular" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_use_purified_css_singular'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="opm_use_purified_css_singular"></label>
		<label class="toggle-label" for="opm_use_purified_css_singular">
		<?php _e('Singular', 'opm') ?>
		</label>
	</div>
	<!-- end singular -->
	<?php if( class_exists( 'WooCommerce' ) ): ?> <!-- check if WooCommerce plugin active -->
	<div class="opm-input-group toggle-group">
		<input class="opm-toggle opm-toggle-light" id="opm_use_purified_css_wc_product" name="opm_use_purified_css_wc_product" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_use_purified_css_wc_product'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="opm_use_purified_css_wc_product"></label>
		<label class="toggle-label" for="opm_use_purified_css_wc_product">
		<?php _e('WooCommerce Products', 'opm') ?>
		</label>
	</div>
	<!-- end input toggle group -->
	<div class="opm-input-group toggle-group">
		<input class="opm-toggle opm-toggle-light" id="opm_use_purified_css_wc_archives" name="opm_use_purified_css_wc_archives" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_use_purified_css_wc_archives'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="opm_use_purified_css_wc_archives"></label>
		<label class="toggle-label" for="opm_use_purified_css_wc_archives">
		<?php _e('WooCommerce Archives', 'opm') ?>
		</label>
	</div>
	<!-- end input toggle group -->
	<?php endif; ?> <!-- end checking if WooCommerce plugin active -->
</div>