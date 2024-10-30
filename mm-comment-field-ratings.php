<?php
/*
Plugin Name:MM Comment Field Ratings
Plugin URI: http://crazydevs.in
Description: MM Comment Field Ratings adds a 5 star rating field to the comment form in WordPress, allowing the site visitor to optionally submit a rating along with their comment.
Version: 1.0
Author: Manidip Mandal
Author URI: http://crazydevs.in
License: GPLv2 or later for adding like and unlike functionality for posts


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


define( 'MMCFR_WP_VERSION_REQUIRED', '3.5');
define( 'MMCFR_PHP_VERSION_REQUIRED', '5.5');
define( 'MMCFR_PLUGIN_NAME', 'mm-comment-field-ratings');
define( 'MMCFR_PLUGIN_URL', plugin_dir_url(  __FILE__  ) );
define( 'MMCFR_PLUGIN_DIR', plugin_basename( __DIR__ ) );
define( 'MMCFR_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'MMCFR_CSS_DIR_URL', MMCFR_PLUGIN_URL.'css/' );
define( 'MMCFR_JS_DIR_URL', MMCFR_PLUGIN_URL.'js/' );
define( 'MMCFR_INC_DIR_PATH', MMCFR_PLUGIN_DIR_PATH.'inc/' );
define( 'MMCFR_VIEW_DIR_PATH', MMCFR_PLUGIN_DIR_PATH.'view/' );
define( 'MMCFR_ASTS_DIR_PATH', MMCFR_PLUGIN_DIR_PATH.'assets/' );
define( 'MMCFR_ASTS_DIR_URL', MMCFR_PLUGIN_URL.'assets/' );
define( 'MMCFR_IMAGE_DIR_PATH', MMCFR_PLUGIN_URL.'images/' );
define( 'MMCFR_PLUGIN_VERSION', '1.0' );

/********************************************************************/

    
/*
 * Compare PHP Version 
 */

if ( version_compare( PHP_VERSION, MMCFR_PHP_VERSION_REQUIRED, '<' ) ) {
    deactivate_plugins( basename( __FILE__ ) );
    wp_die(
        '<p>' .
        sprintf('This plugin can not be activated because it requires a PHP version greater than %1$s. Your PHP version can be updated by your hosting company.', MMCFR_PHP_VERSION_REQUIRED ). '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'Go Back', 'mmcfr' ) . '</a>'
        );
}
/********************************************************************/

/*
 *Include required files
 */

require_once(MMCFR_PLUGIN_DIR_PATH.'mmcfr-common.php');
require_once(MMCFR_INC_DIR_PATH.'metaboxes.php');
require_once(MMCFR_INC_DIR_PATH.'admin.php');
require_once(MMCFR_INC_DIR_PATH.'user.php');



/********************************************************************/



if(!class_exists('MMCFRatings'))
{
    
    class MMCFRatings extends MMCFRCommon
    {
        
        public static $instance;
    
        public static function get_instance(){
            if(NULL == self::$instance){
                self::$instance = new self;
            }else{
                return self::$instance;
            }
        }
      
        function __construct()
        {
            
            parent::__construct();
            add_action('init', array($this,'loadPluginTextDomain'));
            add_filter('plugin_action_links', array($this,'addPluginLinks'), 10, 2);
            register_activation_hook( __FILE__, array($this,'activate'));
            register_deactivation_hook( __FILE__, array($this,'deactivate'));
            register_uninstall_hook(__FILE__,'mmcfr_uninstall');
            
        }

        /*
         *Load plugin text domain.
         */
      
        function loadPluginTextDomain()
        {
            //load_textdomain('mmcfr', MMCFR_PLUGIN_DIR . '/languages/mmcfr-' . get_locale() . '.mo');
            load_plugin_textdomain('mmcfr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
           
        }
      
        public function addPluginLinks($links, $file)
        {
            if ($file == plugin_basename(__FILE__))
              {
                   
                   $settings_link[] = '<a href="' . admin_url('edit.php?post_type=mmcfr-ratings&page=mmcfr-options') . '">' . __('Settings', 'mmcfr') . '</a>';
                   $settings_link[] = '<a href="' . admin_url('edit.php?post_type=mmcfr-ratings') . '">' . __('Rating Fields', 'mmcfr') . '</a>';
                   
                   foreach($settings_link as $settings){
                        array_unshift($links, $settings);
                   }
                   
              }
              return $links;
        }
          
        function activate()
        {
            foreach (MMCFRCommon::$settings_options as $name=>$value)
			{
					add_option($name, $value, '', 'yes');
			}   
          file_put_contents(__DIR__.'/my_loggg.txt', ob_get_contents());
        }
        
        function deactivate()
        {
            
          /* Do nothing */
          
        }

    }    
}


MMCFRatings::get_instance();




?>
