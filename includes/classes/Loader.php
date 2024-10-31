<?php

namespace OptimizeMore;

if (!defined('WPINC')) { die; }

class OpmPluginLoader {
    
    const CLASS_DIR = 'includes/classes/';
    const VIEW_DIR = 'view/';
    
    // plugin core
    private $core_class;
    private $settings_class;
    private $admin_url;
    private $message_class;
    
    // plugin function
    private $parser_class;
    private $non_parser_class;

    // class instance
    private static $_instance;

    function __construct() {
        $this->loadClasses();
    }

    public static function getInstance() {
        if (!self::$_instance) { // if no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function loadClasses() {

        /*
         * plugin core
         */
        $this->require_class('Core');
        $this->core_class = new OpmPluginCore();

        $this->require_class('Settings');
        $this->settings_class = new OpmPluginSettings();
        
        $this->require_class('Messages');
        $this->message_class = new OpmPluginMessages();
        
        // safe guard to prevent the plugin from executing anything if the user haven't save any settings
		if ( !get_option('opm_options') ) {
		  return;
		}
		
		/*
         * plugin function
         */
        
		$this->require_class('Parser');
        $this->parser_class = new Parser();
        
		$this->require_class('NonParser');
        $this->non_parser_class = new NonParser();
        
    }
    
    /*
     * plugin core
     */
    
    public function Core() {
        return $this->core_class;
    }

    public function Settings() {
        return $this->settings_class;
    }
    
    public function Message() {
        return $this->message_class;
    }
    
    public function require_class($file = "") {
        return $this->required(self::CLASS_DIR . $file);
    }

    public function admin_url($view = 'settings') {
        return admin_url('admin.php?page='.OPTIMIZEMORE_SLUG.'&view=' . $view);
    }

    public function required($file = "") {
        $dir = OPTIMIZEMORE_DIR;

        if (empty($dir) || !is_dir($dir)) {
            return false;
        }

        $file = path_join($dir, $file . '.php');

        if (!file_exists($file)) {
            return false;
        }

        require_once $file;
    }

    public function get_view($file = ""){
        $this->required(self::VIEW_DIR . $file);
    }

    public function admin_view($file = ""){
        $this->get_view('admin/' . $file);
    }

    public function get_active_view() {
        $default = 'settings';
        if (!isset($_GET['view'])) {
            return $default;
        }
        $view = wp_filter_kses($_GET['view']);
        return ($view) ? $view : $default;
    }
    
    /*
     * plugin function
     */
     
    public function Parser() {
        return $this->parser_class;
    }
    public function NonParser() {
        return $this->non_parser;
    }
    
    
}