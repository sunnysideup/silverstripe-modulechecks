<?php

Class ModuleTasksConfig extends BuildTask {

    /*
     * A list of module names to process
     * */
    private static $modules = array( 
        'cms_tricks_for_apps', 
        'cms_edit_link_field', 
        'frontendeditor', 
        'payment_stripe', 
        'table_filter_sort', 
        'us_phone_number', 
        'blog_shared_categorisation', 
        'comments_add_recaptcha', 
        'ecommerce_cloud_flare_geoip', 
        'ecommerce_nutritional_products', 
        'ecommerce_stockists', 
        'email_address_database_field', 
        'import_task', 
        'pdf_upload_field', 
        'perfect_cms_images', 
        'phone_field', 
        'share_this_simple', 
        'webpack_requirements_backend', 
        'search_data_objects', 
        'translate'
    );

    /*
     * Default config values where not overridden
     */
    private static $default_module_settings = $array(
        'readme' => 'readme.md',
        'localDir' => '/home/jack/modules/',
        'sourceDir' => '/var/www/picspeanutbutter.com'
    );

    /*
     * Override default settings for specific modules here
     * 
     * */
    private static $module_settings = $array (
        'cms_tricks_for_apps' = array (
            'readme' => 'readme.md',
            'localDir' => '/home/jack/modules/',
            'sourceDir' => '/var/www/picspeanutbutter.com'
            ),
        /*etc ...*/
    
    );

    protected function getModules() {
        return $this->modules;
    }

    /**
     * Returns settings for a speicifed module name
     * 
     * */
    protected function getModuleSettings ($moduleName) {
        $moduleName = trim($moduleName);
        $settings = $this::default_module_settings;
        for ($key in $settings) {
            if (isset($this::module_settings[$moduleName])) {
                if (isset($this::module_settings[$moduleName][$key])) {
                    $settings[$key] = $this::module_settings[$moduleName][$key];
                }
            }
        }
        
    }

}


?>
