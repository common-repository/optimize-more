<?php

namespace OPTIMIZEMORE;

if (!defined('WPINC')) { die; }

class OpmPluginCore {

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_option_menu'));
        
		add_filter('plugin_action_links_' . OPTIMIZEMORE_BASENAME, [$this, 'plugin_setting_links']);
		add_filter('plugin_row_meta', [$this, 'plugin_row_links'], 10, 2);
    }

    /**
     * Add opm to setting menu
     *
     */
    /* add settings on admin sidebar */
    public function add_option_menu()
    {

		$icon_base64 = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0ODcuOCA0ODcuOCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDg3LjggNDg3LjgiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxwYXRoIGQ9Ik0yNDMuOSAwQzEwOS4yIDAgMCAxMDkuMiAwIDI0My45czEwOS4yIDI0My45IDI0My45IDI0My45IDI0My45LTEwOS4yIDI0My45LTI0My45UzM3OC42IDAgMjQzLjkgMHptOTYuNyAxMzEuN2MxNC43IDAgMjYuNiAxMiAyNi42IDI2LjZzLTEyIDI2LjYtMjYuNiAyNi42UzMxNCAxNzMgMzE0IDE1OC40czExLjktMjYuNyAyNi42LTI2Ljd6bS0xNDguOSA0LjZoMTEwLjZjMi45IDAgNS4yIDMgNS4yIDYuOHMtMi4zIDYuOC01LjIgNi44SDE5MS43Yy0yLjkgMC01LjMtMy01LjItNi44LS4xLTMuOCAyLjItNi44IDUuMi02Ljh6bS05OSA1MS40aDExMC42YzIuOSAwIDUuMiAzIDUuMiA2LjhzLTIuMyA2LjgtNS4yIDYuOEg5Mi43Yy0yLjkgMC01LjItMy01LjItNi44czIuMy02LjggNS4yLTYuOHptMTQ1LjYgNTkuNWMyLjkgMCA1LjIgMyA1LjIgNi44cy0yLjMgNi44LTUuMiA2LjhIMTI3LjdjLTIuOSAwLTUuMi0zLTUuMi02LjhzMi4zLTYuOCA1LjItNi44aDExMC42em0tNTAuNiA4MC4ySDg0LjNjLTMgMC01LjUtMy4xLTUuNS03czIuNS03IDUuNS03aDEwMy40YzMgMCA1LjQgMy4yIDUuNSA3IC4xIDMuOC0yLjQgNy01LjUgN3pNMzMyIDIxOS45Yy05IDE1LjUtMTcuNyAzMS4zLTI1LjkgNDcuMyAxMi4xIDYuMyAyNi42IDE3LjIgMzMuOSAyMy4yIDkuMyA3LjQgMjEuMSAzNi40IDI4LjIgNTEgNy42IDE1LjYtMTYuNyAyNy0yNC4zIDExLjQtNi4zLTEyLjktMTYuNy0zOC40LTIyLjgtNDMuNC02LjItNS4xLTI2LjEtMTkuMi00MS40LTI0LjUtLjctLjMtMS41LS42LTIuMS0uOS0xOC43IDIzLjctNTUuMyAzNS04Ni42IDE2LjctMTYuNS05LjYtMS42LTMzLjQgMTQuOS0yMy43IDIyLjkgMTMuNCA0Ni43LTMuOCA1Ny41LTI1LjEgMTAuOS0yMS40IDE4LjUtMzYuNSAyNy4xLTUxLjctMTcuMS01LjQtMzIuNy0uNS00NC45IDE2LjctOS45IDE0LTMzLjMuNi0yMy4yLTEzLjUgMjMuNy0zMy4yIDU5LjItNDEuNSA5NC43LTIwLjQgMS41LjkgOC4xIDQuNiA5LjYgNS40IDIyLjEgMTMuMSA0Mi41IDEwLjYgNTcuOC0xMC45IDkuOS0xNCAzMy4zLS42IDIzLjIgMTMuNi0xOS41IDI3LjItNDYuOSAzNy42LTc1LjcgMjguOHoiLz48L3N2Zz4=';

        //The icon in the data URI scheme
        $icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;
		
		
		$menu = add_menu_page(
            OPTIMIZEMORE_NAME,		// Page title
            OPTIMIZEMORE_NAME,		// Menu name
            'manage_options', 					// Permissions
            OPTIMIZEMORE_SLUG,					// Menu slug
            array($this, 'view'),
			$icon_data_uri
        );

        add_action('load-' . $menu, array($this, 'load'));
        
        if ( is_plugin_active( 'optimize-more-images/optimize-more-images.php' ) ) {
            
            $submenu = add_submenu_page(
                'optimize-more',
                OPTIMIZEMORE_NAME,	// Page title
                OPTIMIZEMORE_PLUGIN_NAME,	// Menu name
                'manage_options', 					// Permissions
                OPTIMIZEMORE_SLUG,					// Menu slug
                array($this, 'view'), 0);
                
            add_action('load-' . $submenu, array($this, 'load'));
            
        }
 
		
    }
    
	
	/* add settings on plugin list */
	public function plugin_setting_links($links)
    {
        $links = array_merge(array(
            '<a href="' . esc_url(admin_url('/admin.php?page=optimize-more')) . '">' . __('Settings', 'opm') . '</a>',
        ), $links);
        
        return $links;
    }
    
    /* add links on plugin list row */
    public function plugin_row_links($links, $file)
      {
        if ($file !== OPTIMIZEMORE_BASENAME ) {
          return $links;
        }
    
        $pro_link = '<a target="_blank" href="https://dhiratara.com/services/speed-optimization/" title="' . __('Optimize More', 'opm') . '">' . __('Optimize More!', 'opm') . '</a>';
    
        $links[] = $pro_link;
    
        return $links;
    } // plugin_meta_links

    /**
     * opm setting menu page is loaded
     *
     */
    public function load()
    {

        do_action("opm_load-page");

        // Register scripts
        add_action("admin_enqueue_scripts", array($this, 'enqueue_scripts'));

        //check store;
        $this->store();
    }

    public function enqueue_scripts()
    {

        $setting_js = 'js/admin-settings.js';
        wp_register_script(
            'opm-admin-settings',
            OPTIMIZEMORE_ASSETS_URL . $setting_js, '',
            OPTIMIZEMORE_VERSION
        );
		
        $ays_before_js = 'js/ays-beforeunload-shim.js';
        wp_register_script(
            'ays-beforeunload-shim',
            OPTIMIZEMORE_ASSETS_URL . $ays_before_js,
            array('jquery'),
            OPTIMIZEMORE_VERSION
        );

        $areyousure_js = 'js/jquery-areyousure.js';
        wp_register_script(
            'jquery-areyousure',
            OPTIMIZEMORE_ASSETS_URL . $areyousure_js,
            array('jquery'),
            OPTIMIZEMORE_VERSION
        );

        $setting_css = 'css/admin-settings.css';
        wp_register_style(
            'opm-admin-settings',
            OPTIMIZEMORE_ASSETS_URL . $setting_css, '',
            OPTIMIZEMORE_VERSION
        );

        wp_enqueue_script(array('ays-beforeunload-shim', 'jquery-areyousure','opm-admin-settings'));
        wp_enqueue_style(array('opm-admin-settings'));
		
        wp_localize_script(
            'opm-admin-settings',
            'opm_settings',
            array(
                'adminurl' => admin_url("index.php"),
                'opm_ajax_nonce' => wp_create_nonce("opm_ajax_nonce")
            )
        );
    }

    private function store()
    {
        do_action('opm_save_before_validation');

        if (!isset($_POST['opm-settings'])) {
            return;
        }

        if (isset($_POST['opm-action']) && $_POST['opm-action'] == 'reset') {
            return;
        }
        //  nonce checking
        if (!isset($_POST['opm_settings_nonce'])
            || !wp_verify_nonce($_POST['opm_settings_nonce'], 'opm-settings-action')) {

            print 'Sorry, your nonce did not verify.';
            exit;
        }

        opm_instance()->Settings()->store();
        return;
    }

    public function view()
    {
        $opm = opm_instance();
        $view = $opm->get_active_view();
        $opm->admin_view($view);
    }    
    
}