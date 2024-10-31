<div class="opm-body-header mt-3">
	<h2><?php _e('WP Rocket Hacks', 'opm') ?></h2>
</div>

<div class="opm-input-group show-hide-group flex-full-width pb-10">
        <div class="opm-input" style="padding-top: 24px;">
            <input class="opm-toggle opm-toggle-light main-toggle show-hide" data-show-hide="1" id="opm_rocket_async_hacks" name="opm_rocket_async_hacks" value="1" type="checkbox"
            <?php checked(OptimizeMore\opm_field_setting( 'opm_rocket_async_hacks'), 1, true) ?>/>
            <label class="opm-toggle-btn" for="opm_rocket_async_hacks"></label>
            <label class="toggle-label" for="opm_rocket_async_hacks">
                <?php _e('Enable Critical CSS + Disable Async CSS', 'opm') ?>
            </label>
            <div class="opm-help">
                <?php _e('Use critical css from WP Rocket but disable the async feature', 'opm') ?>
            </div>
        </div>
        <div id="" class="show-hide-content mt-7">
            <div class="grid col-3">
                <!-- start homepage -->
            	<div class="opm-input-group toggle-group">
            		<input class="opm-toggle opm-toggle-light" id="opm_rocket_async_hacks_home" name="opm_rocket_async_hacks_home" value="1" type="checkbox"
            			<?php checked(OptimizeMore\opm_field_setting( 'opm_rocket_async_hacks_home'), 1, true) ?>/>
            		<label class="opm-toggle-btn" for="opm_rocket_async_hacks_home"></label>
            		<label class="toggle-label" for="opm_rocket_async_hacks_home">
            		<?php _e('Homepage', 'opm') ?>
            		</label>
            	</div>
            	<!-- end homepage -->
            	<!-- start pages -->
            	<div class="opm-input-group toggle-group">
            		<input class="opm-toggle opm-toggle-light" id="opm_rocket_async_hacks_pages" name="opm_rocket_async_hacks_pages" value="1" type="checkbox"
            			<?php checked(OptimizeMore\opm_field_setting( 'opm_rocket_async_hacks_pages'), 1, true) ?>/>
            		<label class="opm-toggle-btn" for="opm_rocket_async_hacks_pages"></label>
            		<label class="toggle-label" for="opm_rocket_async_hacks_pages">
            		<?php _e('Custom Pages', 'opm') ?>
            		</label>
            	</div>
            	<!-- end pages -->
            	<!-- start archives -->
            	<div class="opm-input-group toggle-group">
            		<input class="opm-toggle opm-toggle-light" id="opm_rocket_async_hacks_archives" name="opm_rocket_async_hacks_archives" value="1" type="checkbox"
            			<?php checked(OptimizeMore\opm_field_setting( 'opm_rocket_async_hacks_archives'), 1, true) ?>/>
            		<label class="opm-toggle-btn" for="opm_rocket_async_hacks_archives"></label>
            		<label class="toggle-label" for="opm_rocket_async_hacks_archives">
            		<?php _e('Archives', 'opm') ?>
            		</label>
            	</div>
            	<!-- end archives -->
            	<!-- start singular -->
            	<div class="opm-input-group toggle-group">
            		<input class="opm-toggle opm-toggle-light" id="opm_rocket_async_hacks_singular" name="opm_rocket_async_hacks_singular" value="1" type="checkbox"
            			<?php checked(OptimizeMore\opm_field_setting( 'opm_rocket_async_hacks_singular'), 1, true) ?>/>
            		<label class="opm-toggle-btn" for="opm_rocket_async_hacks_singular"></label>
            		<label class="toggle-label" for="opm_rocket_async_hacks_singular">
            		<?php _e('Singular', 'opm') ?>
            		</label>
            	</div>
            	<!-- end singular -->
            	<?php if( class_exists( 'WooCommerce' ) ): ?> <!-- check if WooCommerce plugin active -->
            	<div class="opm-input-group toggle-group">
            		<input class="opm-toggle opm-toggle-light" id="opm_rocket_async_hacks_wc_product" name="opm_rocket_async_hacks_wc_product" value="1" type="checkbox"
            			<?php checked(OptimizeMore\opm_field_setting( 'opm_rocket_async_hacks_wc_product'), 1, true) ?>/>
            		<label class="opm-toggle-btn" for="opm_rocket_async_hacks_wc_product"></label>
            		<label class="toggle-label" for="opm_rocket_async_hacks_wc_product">
            		<?php _e('WooCommerce Products', 'opm') ?>
            		</label>
            	</div>
            	<!-- end input toggle group -->
            	<div class="opm-input-group toggle-group">
            		<input class="opm-toggle opm-toggle-light" id="opm_rocket_async_hacks_wc_archives" name="opm_rocket_async_hacks_wc_archives" value="1" type="checkbox"
            			<?php checked(OptimizeMore\opm_field_setting( 'opm_rocket_async_hacks_wc_archives'), 1, true) ?>/>
            		<label class="opm-toggle-btn" for="opm_rocket_async_hacks_wc_archives"></label>
            		<label class="toggle-label" for="opm_rocket_async_hacks_wc_archives">
            		<?php _e('WooCommerce Archives', 'opm') ?>
            		</label>
            	</div>
            	<!-- end input toggle group -->
            	<?php endif; ?> <!-- end checking if WooCommerce plugin active -->
			
            </div>
            <!-- end flex wraper -->
        </div>
        <!-- end body wraper -->
    </div>