<div class="opm-body-header">
	<h2><?php _e('Use Extra PHP Files', 'opm') ?></h2>
</div>

<div class="grid col-3 pt-24">

	<div class="opm-input-group toggle-group">
		<input class="opm-toggle opm-toggle-light" id="opm_use_extra_php_file" name="opm_use_extra_php_file" value="1" type="checkbox"
			<?php checked(OptimizeMore\opm_field_setting( 'opm_use_extra_php_file'), 1, true) ?>/>
		<label class="opm-toggle-btn" for="opm_use_extra_php_file"></label>
		<label class="toggle-label" for="opm_use_extra_php_file">
		<?php _e('Enable', 'opm') ?>
		</label>
	</div>
	
</div>