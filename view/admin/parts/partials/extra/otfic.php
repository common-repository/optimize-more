<div class="opm-body-header">
	<h2><?php _e('On the Fly Image Compresssion', 'opm') ?></h2>
</div>

<div class="opm-input-group show-hide-group flex-full-width pt-24">
	<div class="opm-input">
		<input class="opm-toggle opm-toggle-light main-toggle show-hide" data-show-hide="1" id="use_free_img_cdn" name="use_free_img_cdn" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'use_free_img_cdn'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="use_free_img_cdn"></label>
		<label class="toggle-label" for="use_free_img_cdn">
		<?php _e('Enable', 'opm') ?>
		</label>
		<div class="opm-help">
			<?php _e('Enable image compression from free image CDN.', 'opm') ?>
			<?php _e('*for score testing purpose only', 'opm') ?>
		</div>
	</div>
	<div id="" class="show-hide-content mt-7">
		<div class="flex flex grid-col-2">
			<div class="opm-input-group toggle-group flex-50 pt-8 pb-0">
				<input class="opm-toggle opm-toggle-light main-toggle" data-revised="1" id="use_free_img_cdn_statically" name="use_free_img_cdn_statically" value="1" type="checkbox"
					<?php checked(OptimizeMore\opm_field_setting( 'use_free_img_cdn_statically'), 1, true) ?>/>
				<label class="opm-toggle-btn" for="use_free_img_cdn_statically"></label>
				<label class="toggle-label" for="use_free_img_cdn_statically">
				<?php _e('Use Statically CDN', 'opm') ?>
				</label>
			</div>
			<!-- end input toggle group -->
			<div class="opm-input-group toggle-group flex-50 pt-8 pb-0">
				<input class="opm-toggle opm-toggle-light main-toggle" data-revised="1" id="use_free_img_cdn_photon" name="use_free_img_cdn_photon" value="1" type="checkbox"
					<?php checked(OptimizeMore\opm_field_setting( 'use_free_img_cdn_photon'), 1, true) ?>/>
				<label class="opm-toggle-btn" for="use_free_img_cdn_photon"></label>
				<label class="toggle-label" for="use_free_img_cdn_photon">
				<?php _e('Use Photon CDN', 'opm') ?>
				</label>
			</div>
			<!-- end input toggle group -->
			<div class="opm-input-group flex-50 pt-0 pb-8">
				<div class="field-title pt-0 pb-15 pl-2">
					Exclude List
				</div>
				<div class="opm-input">
					<textarea class="textarea-custom" rows="6" name="use_free_img_cdn_exclude_list"><?php echo OptimizeMore\opm_field_setting('use_free_img_cdn_exclude_list') ?></textarea>
				</div>
			</div>
			<!-- end exclude list -->
			<div class="opm-input-group flex-50 pt-0 pb-8">
				<div class="field-title pt-0 pb-15 pl-2">
					Compression Quality
				</div>
				<div class="opm-input">
					<select id="use_free_img_cdn_compression_quality" name="use_free_img_cdn_compression_quality" class="opm-select" style="width:168px">
						<option value="100" <?php if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 100) echo 'selected="selected"'; ?>>100%</option>
						<option value="90" <?php  if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 90 ) echo 'selected="selected"'; ?>>90%</option>
						<option value="80" <?php if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 80 ) echo 'selected="selected"'; ?>>80%</option>
						<option value="70" <?php if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 70 ) echo 'selected="selected"'; ?>>70%</option>
						<option value="60" <?php if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 60 ) echo 'selected="selected"'; ?>>60%</option>
						<option value="50" <?php if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 50 ) echo 'selected="selected"'; ?>>50%</option>
						<option value="40" <?php if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 40 ) echo 'selected="selected"'; ?>>40%</option>
						<option value="30" <?php if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 30 ) echo 'selected="selected"'; ?>>30%</option>
						<option value="20" <?php if (OptimizeMore\opm_field_setting('use_free_img_cdn_compression_quality') == 20 ) echo 'selected="selected"'; ?>>20%</option>
					</select>
				</div>
			</div>
			<!-- end quality -->
		</div>
	</div>
</div>