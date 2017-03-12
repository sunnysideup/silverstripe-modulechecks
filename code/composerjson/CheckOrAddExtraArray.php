<?php
/**
 * sets the default installation folder 
 */
class CheckOrAddExtraArray extends UpdateComposer {


    public function run() {
		$json = $this->composerJsonObj->jsonData;


		if (property_exists($json, 'extra')) {
		
			GeneralMethods::outputToScreen("<li> already has composer.json </li>");
			
			return;
		}

		else {
			GeneralMethods::outputToScreen("<li> Adding 'extra' array to composer.json </li>");
			
			$json->extra  = (object)array('installer-name' => str_replace('silverstripe-', '', $this->composerJsonObj->moduleName)); 
		}
		

    }
}
