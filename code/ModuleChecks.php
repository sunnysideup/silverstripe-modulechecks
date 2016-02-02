<?php


class ModuleChecks extends buildtask {

	/**
	 * @var string
	 */
	private static $git_user_name = "";

	/**
	 * @var string
	 */
	private static $packagist_user_name = "";

	/**
	 * any additional modules to be checked
	 * @var array
	 */
	private static $modules = array();

	/**
	 * list of methods to run for each module
	 * @var array
	 */
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
		$gitUser = $this->Config()->get("git_user_name");
		$packagistUser = $this->Config()->get("git_user_name");
		if($gitUser && $packagistUser) {
			//all is good ...
		}
		else {
			user_error("make sure to set your git and packagist usernames via the standard config system");
		}

		$count = 0;
		$this->getAllRepos();
		echo "<h1>Testing ".count(self::$modules)." modules (git user: $gitUser and packagist user: $packagistUser) ...</h1>";
		$methodsToCheck = $this->Config()->get("methods_to_check");
		foreach(self::$modules as $module) {
			$count++;
			$failures = 0;
			echo "<h3><a href=\"https://github.com/".$gitUser."/silverstripe-".$module."\"></a>$count. checking $module</h3>";
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
		echo "----------------------------- THE END --------------------------";
	}

	/**
	 * @param string $name
	 *
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
	 * takes the preloaded modules and
	 * adds any other ones you have listed on github
	 */
	protected function getAllRepos(){
		$username = $this->Config()->get("git_user_name");
		for($page = 0; $page < 10; $page++) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.github.com/users/".$username."/repos?per_page=100&page=$page");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
			curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
			$string = curl_exec($ch);
			// close curl resource to free up system resources
			curl_close($ch);
			$array = json_decode($string, true);
			$count = count($array);
			if($count > 0 ) {
				foreach($array as $repo) {
					//dont bother about forks
					if(isset($repo["fork"]) && !$repo["fork"]) {
						//make sure we are the owners
						if($repo["owner"]["login"] == $username) {
							//check it is silverstripe module
							$nameWithoutPrefix = preg_replace('/silverstripe/', "", $repo["name"], $limit = 1);
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
			else {
				$page = 11;
			}
		}
	}

	/**
	 * checks if a particular variable is present in the composer.json file
	 *
	 * @param string $name
	 * @param string $variable
	 * @return boolean
	 */
	protected function checkForDetailsInComposerFile($name, $variable){
		die("to be completed");
	}


	/**
	 * opens a location with curl to see if it exists.
	 *
	 * @param string $url
	 *
	 * @return boolean
	 */
	protected function checkLocation($url) {
		$handle = curl_init($url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, TRUE);
		$response = curl_exec($handle);
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$outcome = $httpCode == 200;
		curl_close($handle);
		return $outcome;
	}


}
