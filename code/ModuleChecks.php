<?php


class ModuleChecks extends buildtask {

	private static $git_user_name = "sunnysideup";

	private static $packagist_user_name = "sunnysideup";

	private static $modules = array();

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
			$failures = 0;
			DB::alteration_message("<h3>Checking $module</h3>");
			foreach($methodsToCheck as $method) {
				if(!$this->$method($module)) {
					$failures++;
					DB::alteration_message("bad response for $method", "deleted");
				}
			}
			if($failures == 0) {
				DB::alteration_message("OK", "created");
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
		curl_setopt($ch, CURLOPT_URL, "https://api.github.com/users/".$username."/repos?per_page=500");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$string = curl_exec($ch);
		// close curl resource to free up system resources
		curl_close($ch);
		$array = json_decode($string, true);
		foreach($array as $repo) {
			//dont bother about forks
			if(isset($repo["fork"]) && !$repo["fork"]) {
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
					else {
						DB::alteration_message("skipping ".$repo["name"]." as it does not appear to me a silverstripe module, you can add it manually to this task, using the configs ... ");
					}
				}
				else {
					DB::alteration_message("skipping ".$repo["name"]." as it has a different owner");
				}
			}
			elseif(isset($repo["name"])) {
				DB::alteration_message("skipping ".$repo["name"]." as it is a fork");
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
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, TRUE);
		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$outcome = $httpCode == 200;
		curl_close($handle);
		return $outcome;
	}


}
