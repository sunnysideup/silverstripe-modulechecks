<?php

abstract Class ModuleTasksConfig extends BuildTask {

    /**
     * @var string
     */
    private static $git_user_name = "sunnysideupdevs@gmail.com";

    /**
     * @var string
     */
    private static $packagist_user_name = "sunnysideupdevs@gmail.com";


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
    private static $default_module_settings = array(
        'readme' => 'readme.md',
        'localDir' => '/home/jack/modules/',
        'baseGitHubURL' => "https://github.com/sunnysideup/silverstripe-"
    );


    
    /*
     * return a the list of modules defined in the static
     * */

    protected function getModules() {
        return ModuleTasksConfig::$modules;
    }


    /**
     * Returns settings for a specific module
     * */
    protected function getModuleSettings ($moduleName) {
        $moduleName = trim($moduleName);
        $settings = ModuleTasksConfig::$default_module_settings;
        
        foreach ( $settings as $key => $setting) {
            if (isset(ModuleTasksConfig::$module_settings[$moduleName])) {
                if (isset(ModuleTasksConfig::$module_settings[$moduleName][$key])) {
                    $settings[$key] = ModuleTasksConfig::$module_settings[$moduleName][$key];
                }
            }
        }
        
        return $settings;
    }

    protected function getGitHubModuleObj($moduleName) {
        $moduleName = trim($moduleName);
        $moduleSettings = $this -> getModuleSettings($moduleName);
        
        $gitHubModule = GitHubModule::get()->filter(array('ModuleName'=>$moduleName))->first();

        if (!$gitHubModule) {
 
            $gitHubModule = new GitHubModule();
            
            $gitHubModule->ModuleName = $moduleName;
            $gitHubModule->Directory = $moduleSettings['localDir'].$moduleName;
            $gitHubModule->URL = $moduleSettings['baseGitHubURL'].$moduleName;   
            $gitHubModule->write();
            
        }
        
        return $gitHubModule;
    }
}


?>
