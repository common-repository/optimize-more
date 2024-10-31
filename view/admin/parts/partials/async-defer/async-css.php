<div class="opm-body-header">
	<h2><?php _e('Async Load CSS', 'opm') ?></h2>
</div>

<div class="opm-input-group show-hide-group flex-full-width pt-24">
	<div class="opm-input">
		<input class="opm-toggle opm-toggle-light main-toggle show-hide" data-show-hide="1" id="opm_async_css" name="opm_async_css" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_async_css'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="opm_async_css"></label>
		<label class="toggle-label" for="opm_async_css">
		<?php _e('Enable', 'opm') ?>
		</label>
		<div class="opm-help">
			<?php _e('Enable Loading CSS file(s) Asynchronously', 'opm') ?>
			<?php _e('*use keywords', 'opm') ?>
		</div>
	</div>
	<div id="" class="show-hide-content mt-7">
		<div class="grid col-3">
			<!-- start homepage -->
			<div class="opm-input-group toggle-group">
				<input class="opm-toggle opm-toggle-light main-toggle" data-revised="1" id="opm_async_css_front_page" name="opm_async_css_front_page" value="1" type="checkbox"
					<?php checked(OptimizeMore\opm_field_setting( 'opm_async_css_front_page'), 1, true) ?>/>
				<label class="opm-toggle-btn" for="opm_async_css_front_page"></label>
				<label class="toggle-label" for="opm_async_css_front_page">
				<?php _e('Homepage', 'opm') ?>
				</label>
				<div class="sub-fields pl-0 mt-5">
					<div class="opm-input-group">
						<div class="opm-input">
							<textarea class="textarea-custom" rows="6" name="opm_async_css_front_page_list"><?php echo OptimizeMore\opm_field_setting('opm_async_css_front_page_list') ?></textarea>
						</div>
					</div>
				</div>
				<!-- end of sub fields container -->
			</div>
			<!-- end homepage -->
			
			<!-- start pages -->
			<div class="opm-input-group toggle-group">
				<input class="opm-toggle opm-toggle-light main-toggle" data-revised="1" id="opm_async_css_pages" name="opm_async_css_pages" value="1" type="checkbox"
					<?php checked(OptimizeMore\opm_field_setting( 'opm_async_css_pages'), 1, true) ?>/>
				<label class="opm-toggle-btn" for="opm_async_css_pages"></label>
				<label class="toggle-label" for="opm_async_css_pages">
				<?php _e('Custom Pages', 'opm') ?>
				</label>
				<div class="sub-fields pl-0 mt-5">
					<div class="opm-input-group">
						<div class="opm-input">
							<textarea class="textarea-custom" rows="6" name="opm_async_css_pages_list"><?php echo OptimizeMore\opm_field_setting('opm_async_css_pages_list') ?></textarea>
						</div>
					</div>
				</div>
				<!-- end of sub fields container -->
			</div>
			<!-- end pages -->
			
			<!-- start archives -->
			<div class="opm-input-group toggle-group">
				<input class="opm-toggle opm-toggle-light main-toggle" data-revised="1" id="opm_async_css_archives" name="opm_async_css_archives" value="1" type="checkbox"
					<?php checked(OptimizeMore\opm_field_setting( 'opm_async_css_archives'), 1, true) ?>/>
				<label class="opm-toggle-btn" for="opm_async_css_archives"></label>
				<label class="toggle-label" for="opm_async_css_archives">
				<?php _e('Archives', 'opm') ?>
				</label>
				<div class="sub-fields pl-0 mt-5">
					<div class="opm-input-group">
						<div class="opm-input">
							<textarea class="textarea-custom" rows="6" name="opm_async_css_archives_list"><?php echo OptimizeMore\opm_field_setting('opm_async_css_archives_list') ?></textarea>
						</div>
					</div>
				</div>
				<!-- end of sub fields container -->
			</div>
			<!-- end archives -->
			
			<!-- start singular -->
			<div class="opm-input-group toggle-group">
				<input class="opm-toggle opm-toggle-light main-toggle" data-revised="1" id="opm_async_css_singular" name="opm_async_css_singular" value="1" type="checkbox"
					<?php checked(OptimizeMore\opm_field_setting( 'opm_async_css_singular'), 1, true) ?>/>
				<label class="opm-toggle-btn" for="opm_async_css_singular"></label>
				<label class="toggle-label" for="opm_async_css_singular">
				<?php _e('Singular', 'opm') ?>
				</label>
				<div class="sub-fields pl-0 mt-5">
					<div class="opm-input-group">
						<div class="opm-input">
							<textarea class="textarea-custom" rows="6" name="opm_async_css_singular_list"><?php echo OptimizeMore\opm_field_setting('opm_async_css_singular_list') ?></textarea>
						</div>
					</div>
				</div>
				<!-- end of sub fields container -->
			</div>
			<!-- end singular -->
			<?php if( class_exists( 'WooCommerce' ) ): ?> <!-- check if WooCommerce plugin active -->
			<div class="opm-input-group toggle-group">
				<input class="opm-toggle opm-toggle-light main-toggle" data-revised="1" id="opm_async_css_wc_product" name="opm_async_css_wc_product" value="1" type="checkbox"
					<?php checked(OptimizeMore\opm_field_setting( 'opm_async_css_wc_product'), 1, true) ?>/>
				<label class="opm-toggle-btn" for="opm_async_css_wc_product"></label>
				<label class="toggle-label" for="opm_async_css_wc_product">
				<?php _e('WooCommerce Products', 'opm') ?>
				</label>
				<div class="sub-fields pl-0 mt-5">
					<!-- start of sub fields container -->
					<div class="opm-input-group">
						<div class="opm-input">
							<textarea class="textarea-custom" rows="6" name="opm_async_css_wc_product_list"><?php echo OptimizeMore\opm_field_setting('opm_async_css_wc_product_list') ?></textarea>
						</div>
					</div>
				</div>
				<!-- end of sub fields container -->
			</div>
			<!-- end input toggle group -->
			<div class="opm-input-group toggle-group">
				<input class="opm-toggle opm-toggle-light main-toggle" data-revised="1" id="opm_async_css_wc_archives" name="opm_async_css_wc_archives" value="1" type="checkbox"
					<?php checked(OptimizeMore\opm_field_setting( 'opm_async_css_wc_archives'), 1, true) ?>/>
				<label class="opm-toggle-btn" for="opm_async_css_wc_archives"></label>
				<label class="toggle-label" for="opm_async_css_wc_archives">
				<?php _e('WooCommerce Archives', 'opm') ?>
				</label>
				<div class="sub-fields pl-0 mt-5">
					<!-- start of sub fields container -->
					<div class="opm-input-group">
						<div class="opm-input">
							<textarea class="textarea-custom" rows="6" name="opm_async_css_wc_archives_list"><?php echo OptimizeMore\opm_field_setting('opm_async_css_wc_archives_list') ?></textarea>
						</div>
					</div>
				</div>
				<!-- end of sub fields container -->
			</div>
			<!-- end input toggle group -->
			<?php endif; ?> <!-- end checking if WooCommerce plugin active -->
		</div>
		<!-- end flex wraper -->
	</div>
	<!-- end body wraper -->
</div>