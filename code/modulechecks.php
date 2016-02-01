<?php


class modulechecks extends buildtask {

	private static $git_user_name = "sunnysideup";

	private static $packagist_user_name = "sunnysideup";

	private static $modules = array(
		'advertisements',
		'affiliations',
		'business_directory',
		'calendar',
		'campaignmonitor',
		'copyfactory',
		'databasebackup',
		'dataintegritytests',
		'dataobjectsorter',
		'datefield_simplified',
		'designers',
		'docs',
		'downloadtoemail',
		'ecommerce_test',
		'emailreferral',
		'faqs',
		'flash',
		'flowplayer',
		'fontresizer',
		'formfieldexplanations',
		'forsale',
		'geobrowser',
		'google_address_field',
		'googleanalyticsbasics',
		'googlecustomsearch',
		'googlemap',
		'googlemapbasic',
		'hidemailto',
		'htmleditoroptions',
		'image_placeholder_replacer',
		'imagegallery_basic',
		'manymonthscalendar',
		'membermanagement',
		'membersonlypages',
		'menucache',
		'metatags',
		'move-silverstripe-site',
		'mysql_ansi',
		'newsletter_bounce',
		'newsletter_emogrify',
		'newsletter_viewarchive',
		'pagenotfound',
		'pagerater',
		'pdfcrowd',
		'picasa_randomizer',
		'prettyphoto',
		'quicktimevideo',
		'required_fields_validation',
		'searchengine',
		'searchplus',
		'sharethis',
		'sifr',
		'simplestspam',
		'sitemappage',
		'sitetreeformfields',
		'smartchimp',
		'social_integration',
		'staffprofiles',
		'superfish',
		'templateoverview',
		'termsandconditions',
		'testmailer',
		'thickbox',
		'traininglistingsandsignup',
		'typography',
		'upgrade_silverstripe',
		'userforms_paypal',
		'userforms_relatives',
		'userpage',
		'vimeoembed',
		'webportfolio',
		'widgetextensions',
		'widgets_childentries',
		'widgets_currencyconverter',
		'widgets_didyouknow',
		'widgets_headlines',
		'widgets_latestblogentries',
		'widgets_latestpagesvisited',
		'widgets_newfeaturedvideo',
		'widgets_quicklinks',
		'widgets_quotes',
		'widgets_richadvertisement',
		'widgets_sidetext',
		'widgets_tagcloudall',
		'widgets_widgetadvertisement',
		'wiki',
		'wishlist',
		'youtubegallery',
		'ecommerce',
		'ecommerce_alsorecommended',
		'ecommerce_alternativeproductgroup',
		'ecommerce_anypriceproduct',
		'ecommerce_au_connectivity',
		'ecommerce_brandbrowsing',
		'ecommerce_check_availability',
		'ecommerce_club_order',
		'ecommerce_combo_product',
		'ecommerce_complex_pricing',
		'ecommerce_corporate_account',
		'ecommerce_countries',
		'ecommerce_delivery',
		'ecommerce_delivery_custom',
		'ecommerce_delivery_electronic',
		'ecommerce_dimensions',
		'ecommerce_discount_coupon',
		'ecommerce_googleanalytics',
		'ecommerce_import',
		'ecommerce_merchants',
		'ecommerce_modifier_example',
		'ecommerce_mybusinessworld',
		'ecommerce_newsletter',
		'ecommerce_newsletter_campaign_monitor',
		'ecommerce_nz_connectivity',
		'ecommerce_omnipay',
		'ecommerce_product_questions',
		'ecommerce_product_tags',
		'ecommerce_product_variation',
		'ecommerce_product_variation_colours',
		'ecommerce_quick_add',
		'ecommerce_repeatorders',
		'ecommerce_rewards',
		'ecommerce_search',
		'ecommerce_shipping_fastwaynz',
		'ecommerce_software',
		'ecommerce_statistics',
		'ecommerce_stockcontrol',
		'ecommerce_tax',
		'ecommerce_trademe',
		'ecommerce_unleashed',
		'payment_authorizedotnet',
		'payment_buckaroo',
		'payment_directcredit',
		'payment_dps',
		'payment_epaydk',
		'payment_eway',
		'payment_instore',
		'payment_ogone',
		'payment_paymate',
		'payment_paymentexpress',
		'payment_paypal',
		'payment_paystation_hosted',
		'payment_securatech'
	);

	private static $methods_to_check = array(
		"exitsOnPackagist",
		"hasReadMeFile",
		"hasLicense",
		"hasComposerFile",
		"existsOnAddOns",
	);

	protected $title = "Check Modules on Github and Packagist";

	protected $description = "Goes through every module on github and checks for some of the basic requirements. You will need to set your GitHub Username in the configs.";

	function run($request) {

		increase_time_limit_to(3600);
		$this->getAllRepos();
		$methodsToCheck = $this->Config()->get("methods_to_check");
		foreach(self::$modules as $module) {
			DB::alteration_message("<h3>Checking $module</h3>");
			foreach($methodsToCheck as $method) {
				if(!$this->$method($module)) {
					DB::alteration_message("bad response for $method", "deleted");
				}
			}
			ob_flush();
			flush();
		}
	}

	/**
	 * @param string $name
	 * @return boolean
	 */
	protected function exitsOnPackagist($name){
		return $this->checkLocation("https://packagist.org/packages/".$this->Config()->get("packagist_user_name")."/$name");
	}


	/**
	 * @param string $name
	 *
	 * @return boolean
	 */
	protected function hasLicense($name){
		return $this->checkLocation("https://raw.githubusercontent.com/".$this->Config()->get("git_user_name")."/silverstripe-".$name."/master/LICENSE");
	}

	/**
	 * @param string $name
	 *
	 * @return boolean
	 */
	protected function hasComposerFile($name){
		return $this->checkLocation("https://raw.githubusercontent.com/".$this->Config()->get("git_user_name")."/silverstripe-".$name."/master/composer.json");
	}

	/**
	 * @param string $name
	 *
	 * @return boolean
	 */
	protected function hasReadMeFile($name){
		return $this->checkLocation("https://raw.githubusercontent.com/".$this->Config()->get("git_user_name")."/silverstripe-".$name."/master/README.md");
	}

	protected function existsOnAddOns($name) {
		return $this->checkLocation("http://addons.silverstripe.org/add-ons/".$this->Config()->get("packagist_user_name")."/$name");
	}

	/**
	 * takes the preloaded modules and add any other ones you have listed...
	 */
	protected function getAllRepos(){
		$username = $this->Config()->get("git_user_name");
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "https://api.github.com/users/".$username."/repos");
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		// $output contains the output string
		$string = curl_exec($ch);

		// close curl resource to free up system resources
		curl_close($ch);
		$array = json_decode($string, true);
		foreach($array as $repo) {
			//dont bother about forks
			if(!$repo["fork"]) {
				//make sure we are the owners
				if($repo["owner"]["login"] == $username) {
					//check it is silverstripe module
					$nameWithoutPrefix = str_replace("silverstripe-", "", $repo["name"]);
					if(strlen($nameWithoutPrefix) < strlen($repo["name"])) {
						//is it listed yet?
						if(!in_array($nameWithoutPrefix, self::$modules)) {
							self::$modules[] = $nameWithoutPrefix;
						}
					}
				}
			}
		}
	}

	protected function checkForDetailsInComposerFile(){
		die("to be completed");
		//check require in composer.json
		$data = file_get_contents($location);
		// Is the str in the data (case-insensitive search)
		if (stripos($data, "\"require\":{") !== false){
			// sw00t! we have a match
			echo "<div style='color: green;' class='ok'>OK $location has require</div>";
		}
		else {
			echo "<div style='color: red;'>BAD $location does not have require</div>";
		}

		//check require in composer.json
		$data = file_get_contents($location);
		// Is the str in the data (case-insensitive search)
		if (stripos($data, "\"extra\":{") !== false){
			// sw00t! we have a match
			echo "<div style='color: green;' class='ok'>OK $location has extra</div>";
		}
		else {
			echo "<div style='color: red;'>BAD $location does not have extra</div>";
		}

		//check authors in composer.json
		$data = file_get_contents($location);
		// Is the str in the data (case-insensitive search)
		if (stripos($data, "\"authors\":[") !== false){
			// sw00t! we have a match
			echo "<div style='color: green;' class='ok'>OK $location has authors</div>";
		}
		else {
			echo "<div style='color: red;'>BAD $location does not have authors</div>";
		}
	}


	protected function checkLocation($url) {
		$handle = curl_init($url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$outcome = $httpCode == 200;
		curl_close($handle);
		return $outcome;
	}


}
