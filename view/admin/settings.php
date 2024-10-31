<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

$views = array(
        "async-defer" => __('Async / Defer'),
        "delay" => __('Delay'),
        "preload" => __('Preload'),
        "remove" => __('Remove'),
        "inline" => __('Inline'),
        "misc" => __('Misc'),
        "extra" => __('Extra'),
    );

if (!is_plugin_active('optimize-more-extra/dhiratara.php')) {
	unset($views["extra"]);
}

?>

<div class="opm-plugin-wrapper">

    <div class="opm-header">
                <h1 class="opm-page_title"><?php echo esc_html(OPTIMIZEMORE_NAME); ?><span> v. <?php echo esc_html(OPTIMIZEMORE_VERSION); ?></span></h1>
			<p class="opm-page_description"><?php echo esc_html(OPTIMIZEMORE_DESCRIPTION); ?></p>
            </div>
    <div class="opm-wrapper">
        <div class="opm-messages">
            <?php do_action("OptimizeMore\OpmPluginMessages");?>
            <span></span>
        </div>
    	
    	<div class="opm-navigation navigation flex">
    	    
                <ul class="nav">
                    <?php
                    foreach($views as $slug => $view):
                    ?>
                    <li class="opm-tab-<?php echo esc_html( $slug ) ?>">
                        <a href="#tab-<?php echo esc_html( $slug ) ?>" data-tab="tab-<?php echo esc_html( $slug ) ?>" id="opm-tab-<?php echo esc_html( $slug ) ?>"<?php esc_html( $slug ) == 'floating' ? ' class="current"' : ''?>><?php _e($view, 'opm'); ?></a>
                    </li>
                    <?php
                    endforeach;
                    ?>
                    <?php do_action("opm_after_menu_tab"); ?>
                </ul>
                
                <ul class="mt-auto small-padding">
                    <li><a href="#tab-import-settings" data-tab="tab-import-settings" id="opm_tab_import-settings"><?php _e('Import Settings')?></a></li>
                    <li><a href="<?php echo esc_url(admin_url('admin.php?page=opm&opm-action=export')) ?>" class="opm-ignore"><?php _e('Export Settings') ?></a></li>
                    <li><a href="<?php echo esc_url(admin_url('admin.php?page=opm&opm-action=reset')) ?>" class="opm-ignore reset-confirm"><?php _e('Reset Plugin')?></a></li>
                </ul>
                
        </div>
    	
        <form method="post" enctype="multipart/form-data" class="opm-form" action="<?php echo opm_instance()->admin_url(); ?>" >
            <?php wp_nonce_field('opm-settings-action', 'opm_settings_nonce'); ?>
            
            <div class="opm-content">
                <?php
                
                do_action("opm_before_body");
                
                foreach ($views as $slug => $view) :
                    print '<section class="tab-'. esc_html( $slug ) .'" id="'. esc_html( $slug ) .'">';
                    opm_instance()->admin_view( 'parts/' . esc_html( $slug ));
                    print '</section>';
                endforeach;
                
                do_action("opm_after_body");
                ?>
            </div>
    		
    	<div class="opm-save-settings">
                    <input type="submit" value="<?php _e('Save Changes', 'opm') ?>" class="button button-primary button-large" name="opm-settings" />
        </div>
        </form>
        
        <div class="opm-sidebar">
            <?php opm_instance()->admin_view('parts/sidebar'); ?>
        </div>
        
    </div>

</div>
<?php
wp_enqueue_media();