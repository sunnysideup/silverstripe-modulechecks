2020-06-05 05:52

# running php upgrade upgrade see: https://github.com/silverstripe/silverstripe-upgrader
cd /var/www/ss3/upgrades/modulechecks
php /var/www/ss3/upgrader/vendor/silverstripe/upgrader/bin/upgrade-code upgrade /var/www/ss3/upgrades/modulechecks/modulechecks  --root-dir=/var/www/ss3/upgrades/modulechecks --write -vvv
Array
(
    [0] => Running upgrades on "/var/www/ss3/upgrades/modulechecks/modulechecks"
    [1] => [2020-06-05 17:52:19] Applying RenameClasses to _config.php...
    [2] => [2020-06-05 17:52:19] Applying ClassToTraitRule to _config.php...
    [3] => [2020-06-05 17:52:19] Applying RenameClasses to ModulechecksTest.php...
    [4] => [2020-06-05 17:52:19] Applying ClassToTraitRule to ModulechecksTest.php...
    [5] => [2020-06-05 17:52:19] Applying RenameClasses to UpdateComposer.php...
    [6] => [2020-06-05 17:52:19] Applying ClassToTraitRule to UpdateComposer.php...
    [7] => [2020-06-05 17:52:19] Applying RenameClasses to RunCommandLineMethodOnModule.php...
    [8] => [2020-06-05 17:52:19] Applying ClassToTraitRule to RunCommandLineMethodOnModule.php...
    [9] => [2020-06-05 17:52:19] Applying RenameClasses to AddFileToModule.php...
    [10] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddFileToModule.php...
    [11] => [2020-06-05 17:52:19] Applying RenameClasses to GeneralMethods.php...
    [12] => [2020-06-05 17:52:19] Applying ClassToTraitRule to GeneralMethods.php...
    [13] => [2020-06-05 17:52:19] Applying RenameClasses to GitRepoFinder.php...
    [14] => [2020-06-05 17:52:19] Applying ClassToTraitRule to GitRepoFinder.php...
    [15] => [2020-06-05 17:52:19] Applying RenameClasses to AddGitAttributesToModule.php...
    [16] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddGitAttributesToModule.php...
    [17] => [2020-06-05 17:52:19] Applying RenameClasses to AddGitIgnoreToModule.php...
    [18] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddGitIgnoreToModule.php...
    [19] => [2020-06-05 17:52:19] Applying RenameClasses to AddHtAccessToModule.php...
    [20] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddHtAccessToModule.php...
    [21] => [2020-06-05 17:52:19] Applying RenameClasses to AddTravisYmlToModule.php...
    [22] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddTravisYmlToModule.php...
    [23] => [2020-06-05 17:52:19] Applying RenameClasses to AddEditorConfigToModule.php...
    [24] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddEditorConfigToModule.php...
    [25] => [2020-06-05 17:52:19] Applying RenameClasses to AddContributingToModule.php...
    [26] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddContributingToModule.php...
    [27] => [2020-06-05 17:52:19] Applying RenameClasses to AddUserguideMdToModule.php...
    [28] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddUserguideMdToModule.php...
    [29] => [2020-06-05 17:52:19] Applying RenameClasses to AddSourceReadmeToModule.php...
    [30] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddSourceReadmeToModule.php...
    [31] => [2020-06-05 17:52:19] Applying RenameClasses to AddLicenceToModule.php...
    [32] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddLicenceToModule.php...
    [33] => [2020-06-05 17:52:19] Applying RenameClasses to AddScrutinizerYmlToModule.php...
    [34] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddScrutinizerYmlToModule.php...
    [35] => [2020-06-05 17:52:19] Applying RenameClasses to AddGitAttribuesToModule.php...
    [36] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddGitAttribuesToModule.php...
    [37] => [2020-06-05 17:52:19] Applying RenameClasses to AddChangeLogToModule.php...
    [38] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddChangeLogToModule.php...
    [39] => [2020-06-05 17:52:19] Applying RenameClasses to AddTestToModule.php...
    [40] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddTestToModule.php...
    [41] => [2020-06-05 17:52:19] Applying RenameClasses to AddManifestExcludeToModule.php...
    [42] => [2020-06-05 17:52:19] Applying ClassToTraitRule to AddManifestExcludeToModule.php...
    [43] => [2020-06-05 17:52:19] Applying RenameClasses to UpdateLicense.php...
    [44] => [2020-06-05 17:52:19] Applying ClassToTraitRule to UpdateLicense.php...
    [45] => [2020-06-05 17:52:19] Applying RenameClasses to UpdateModuleType.php...
    [46] => [2020-06-05 17:52:19] Applying ClassToTraitRule to UpdateModuleType.php...
    [47] => [2020-06-05 17:52:19] Applying RenameClasses to CheckOrAddExtraArray.php...
    [48] => [2020-06-05 17:52:19] Applying ClassToTraitRule to CheckOrAddExtraArray.php...
    [49] => [2020-06-05 17:52:19] Applying RenameClasses to UpdataModuleType.php...
    [50] => [2020-06-05 17:52:19] Applying ClassToTraitRule to UpdataModuleType.php...
    [51] => [2020-06-05 17:52:19] Applying RenameClasses to ModuleConfigInterface.php...
    [52] => 
    [53] => In ParserAbstract.php line 293:
    [54] => 
    [55] =>   [PhpParser\Error]
    [56] =>   Syntax error, unexpected T_INTERFACE, expecting T_STRING on line 3
    [57] => 
    [58] => 
    [59] => Exception trace:
    [60] =>   at /var/www/ss3/upgrader/vendor/nikic/php-parser/lib/PhpParser/ParserAbstract.php:293
    [61] =>  PhpParser\ParserAbstract->parse() at /var/www/ss3/upgrader/vendor/nikic/php-parser/lib/PhpParser/Parser/Multiple.php:50
    [62] =>  PhpParser\Parser\Multiple->tryParse() at /var/www/ss3/upgrader/vendor/nikic/php-parser/lib/PhpParser/Parser/Multiple.php:31
    [63] =>  PhpParser\Parser\Multiple->parse() at /var/www/ss3/upgrader/vendor/silverstripe/upgrader/src/Util/MutableSource.php:45
    [64] =>  SilverStripe\Upgrader\Util\MutableSource->__construct() at /var/www/ss3/upgrader/vendor/silverstripe/upgrader/src/UpgradeRule/PHP/RenameClasses.php:58
    [65] =>  SilverStripe\Upgrader\UpgradeRule\PHP\RenameClasses->upgradeFile() at /var/www/ss3/upgrader/vendor/silverstripe/upgrader/src/Upgrader.php:61
    [66] =>  SilverStripe\Upgrader\Upgrader->upgrade() at /var/www/ss3/upgrader/vendor/silverstripe/upgrader/src/Console/UpgradeCommand.php:95
    [67] =>  SilverStripe\Upgrader\Console\UpgradeCommand->execute() at /var/www/ss3/upgrader/vendor/symfony/console/Command/Command.php:255
    [68] =>  Symfony\Component\Console\Command\Command->run() at /var/www/ss3/upgrader/vendor/symfony/console/Application.php:1000
    [69] =>  Symfony\Component\Console\Application->doRunCommand() at /var/www/ss3/upgrader/vendor/symfony/console/Application.php:271
    [70] =>  Symfony\Component\Console\Application->doRun() at /var/www/ss3/upgrader/vendor/symfony/console/Application.php:147
    [71] =>  Symfony\Component\Console\Application->run() at /var/www/ss3/upgrader/vendor/silverstripe/upgrader/bin/upgrade-code:55
    [72] => 
    [73] => upgrade [-r|--rule RULE] [-p|--prompt] [-d|--root-dir ROOT-DIR] [-w|--write] [--] <path>
    [74] => 
)

# running php upgrade upgrade see: https://github.com/silverstripe/silverstripe-upgrader
cd /var/www/ss3/upgrades/modulechecks
php /var/www/ss3/upgrader/vendor/silverstripe/upgrader/bin/upgrade-code upgrade /var/www/ss3/upgrades/modulechecks/modulechecks  --root-dir=/var/www/ss3/upgrades/modulechecks --write -vvv
Writing changes for 36 files
Running upgrades on "/var/www/ss3/upgrades/modulechecks/modulechecks"
[2020-06-05 18:13:46] Applying RenameClasses to _config.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to _config.php...
[2020-06-05 18:13:46] Applying RenameClasses to ModulechecksTest.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to ModulechecksTest.php...
[2020-06-05 18:13:46] Applying RenameClasses to UpdateComposer.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to UpdateComposer.php...
[2020-06-05 18:13:46] Applying RenameClasses to RunCommandLineMethodOnModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to RunCommandLineMethodOnModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddFileToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddFileToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to GeneralMethods.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to GeneralMethods.php...
[2020-06-05 18:13:46] Applying RenameClasses to GitRepoFinder.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to GitRepoFinder.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddGitAttributesToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddGitAttributesToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddGitIgnoreToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddGitIgnoreToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddHtAccessToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddHtAccessToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddTravisYmlToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddTravisYmlToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddEditorConfigToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddEditorConfigToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddContributingToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddContributingToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddUserguideMdToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddUserguideMdToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddSourceReadmeToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddSourceReadmeToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddLicenceToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddLicenceToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddScrutinizerYmlToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddScrutinizerYmlToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddGitAttribuesToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddGitAttribuesToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddChangeLogToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddChangeLogToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddTestToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddTestToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to AddManifestExcludeToModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to AddManifestExcludeToModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to UpdateLicense.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to UpdateLicense.php...
[2020-06-05 18:13:46] Applying RenameClasses to UpdateModuleType.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to UpdateModuleType.php...
[2020-06-05 18:13:46] Applying RenameClasses to CheckOrAddExtraArray.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to CheckOrAddExtraArray.php...
[2020-06-05 18:13:46] Applying RenameClasses to UpdataModuleType.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to UpdataModuleType.php...
[2020-06-05 18:13:46] Applying RenameClasses to ModuleConfigInterface.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to ModuleConfigInterface.php...
[2020-06-05 18:13:46] Applying RenameClasses to FixPSR2.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to FixPSR2.php...
[2020-06-05 18:13:46] Applying RenameClasses to SetPermissions.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to SetPermissions.php...
[2020-06-05 18:13:46] Applying RenameClasses to RemoveOrig.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to RemoveOrig.php...
[2020-06-05 18:13:46] Applying RenameClasses to RemoveSVN.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to RemoveSVN.php...
[2020-06-05 18:13:46] Applying RenameClasses to RemoveAPI.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to RemoveAPI.php...
[2020-06-05 18:13:46] Applying RenameClasses to FixConfigBasics.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to FixConfigBasics.php...
[2020-06-05 18:13:46] Applying RenameClasses to ConfigYML.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to ConfigYML.php...
[2020-06-05 18:13:46] Applying RenameClasses to ComposerJson.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to ComposerJson.php...
[2020-06-05 18:13:46] Applying RenameClasses to GitHubModule.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to GitHubModule.php...
[2020-06-05 18:13:46] Applying RenameClasses to ModuleChecks.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to ModuleChecks.php...
[2020-06-05 18:13:46] Applying RenameClasses to UpdateModules.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to UpdateModules.php...
[2020-06-05 18:13:46] Applying UpdateConfigClasses to database.legacy.yml...
[2020-06-05 18:13:46] Applying RenameClasses to whitespace.php...
[2020-06-05 18:13:46] Applying ClassToTraitRule to whitespace.php...
modified:	tests/ModulechecksTest.php
@@ -1,4 +1,6 @@
 <?php
+
+use SilverStripe\Dev\SapphireTest;

 class ModulechecksTest extends SapphireTest
 {

modified:	src/Api/UpdateComposer.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\Api;

-use ViewableData;
+
+use SilverStripe\View\ViewableData;
+

 /**
  * ### @@@@ START REPLACEMENT @@@@ ###

modified:	src/Api/RunCommandLineMethodOnModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\Api;

-use ViewableData;
+
+use SilverStripe\View\ViewableData;
+

 /**
  * ### @@@@ START REPLACEMENT @@@@ ###

modified:	src/Api/AddFileToModule.php
@@ -2,11 +2,18 @@

 namespace Sunnysideup\ModuleChecks\Api;

-use Director;
-use Filesystem;
-use GitHubModule;
-use Injector;
-use ViewableData;
+
+
+
+
+
+use SilverStripe\View\Requirements;
+use SilverStripe\Control\Director;
+use SilverStripe\Assets\Filesystem;
+use SilverStripe\Core\Injector\Injector;
+use Sunnysideup\ModuleChecks\Objects\GitHubModule;
+use SilverStripe\View\ViewableData;
+

 /**
  * adds or replaces a file
@@ -36,7 +43,7 @@
     protected $replaceArray = [
         '+++README_DOCUMENTATION+++' => 'Documentation',
         '+++README_SUGGESTED_MODULES+++' => 'SuggestedModules',
-        '+++README_REQUIREMENTS+++' => 'Requirements',
+        '+++README_REQUIREMENTS+++' => Requirements::class,
         '+++README_INSTALLATION+++' => 'Installation',
         '+++README_AUTHOR+++' => 'Author',
         '+++README_ASSISTANCE+++' => 'Assistance',

modified:	src/Api/GeneralMethods.php
@@ -2,10 +2,14 @@

 namespace Sunnysideup\ModuleChecks\Api;

-use DB;
-use Director;
+
+
 use FileSystem;
-use ViewableData;
+
+use SilverStripe\Control\Director;
+use SilverStripe\ORM\DB;
+use SilverStripe\View\ViewableData;
+

 /**
  * ### @@@@ START REPLACEMENT @@@@ ###

modified:	src/Api/GitRepoFinder.php
@@ -2,11 +2,17 @@

 namespace Sunnysideup\ModuleChecks\Api;

-use Config;
-use DB;
-use GitHubModule;
-use UpdateModules;
-use ViewableData;
+
+
+
+
+
+use SilverStripe\Core\Config\Config;
+use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
+use Sunnysideup\ModuleChecks\Objects\GitHubModule;
+use SilverStripe\ORM\DB;
+use SilverStripe\View\ViewableData;
+

 /**
  * ### @@@@ START REPLACEMENT @@@@ ###
@@ -40,12 +46,12 @@

     public static function get_all_repos_no_oauth($username = '', $getNamesWithPrefix = false)
     {
-        $preSelected = Config::inst()->get('UpdateModules', 'modules_to_update');
+        $preSelected = Config::inst()->get(UpdateModules::class, 'modules_to_update');
         if (is_array($preSelected) && count($preSelected)) {
             return $preSelected;
         }
         if (! $username) {
-            $username = Config::inst()->get('GitHubModule', 'github_user_name');
+            $username = Config::inst()->get(GitHubModule::class, 'github_user_name');
         }
         print "<li>Retrieving List of modules from GitHub for user ${username} ... </li>";
         if (! count(self::$_modules)) {
@@ -106,14 +112,14 @@

     public static function get_repos_with_auth($username = '', $getNamesWithPrefix = false)
     {
-        $preSelected = Config::inst()->get('UpdateModules', 'modules_to_update');
+        $preSelected = Config::inst()->get(UpdateModules::class, 'modules_to_update');
         if (is_array($preSelected) && count($preSelected)) {
             self::$_modules = $preSelected;
         } else {
             if ($username) {
                 $gitUserName = $username;
             } else {
-                $gitUserName = Config::inst()->get('GitHubModule', 'github_user_name');
+                $gitUserName = Config::inst()->get(GitHubModule::class, 'github_user_name');
             }
             print "<li>Retrieving List of modules from GitHub for user ${username} ... </li>";
             if (! count(self::$_modules)) {

modified:	src/FilesToAdd/AddGitAttributesToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddGitAttributesToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddGitIgnoreToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddGitIgnoreToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddHtAccessToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddHtAccessToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddTravisYmlToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddTravisYmlToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddEditorConfigToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddEditorConfigToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddContributingToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddContributingToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddUserguideMdToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddUserguideMdToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddSourceReadmeToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddSourceReadmeToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddLicenceToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddLicenceToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddScrutinizerYmlToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddScrutinizerYmlToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddGitAttribuesToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddGitAttribuesToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddChangeLogToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddChangeLogToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddTestToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddTestToModule extends AddFileToModule
 {

modified:	src/FilesToAdd/AddManifestExcludeToModule.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\FilesToAdd;

-use AddFileToModule;
+
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+

 class AddManifestExcludeToModule extends AddFileToModule
 {

modified:	src/Composerjson/UpdateLicense.php
@@ -2,8 +2,12 @@

 namespace Sunnysideup\ModuleChecks\Composerjson;

-use Config;
-use UpdateComposer;
+
+
+use SilverStripe\Core\Config\Config;
+use Sunnysideup\ModuleChecks\Composerjson\UpdateLicense;
+use Sunnysideup\ModuleChecks\Api\UpdateComposer;
+

 /**
  * sets the default installation folder
@@ -15,7 +19,7 @@
     public function run()
     {
         $json = $this->getJsonData();
-        $json['license'] = Config::inst()->get('UpdateLicense', 'license_type');
+        $json['license'] = Config::inst()->get(UpdateLicense::class, 'license_type');

         $this->setJsonData($json);
     }

modified:	src/Composerjson/UpdateModuleType.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\Composerjson;

-use UpdateComposer;
+
+use Sunnysideup\ModuleChecks\Api\UpdateComposer;
+

 /**
  * sets the default installation folder

modified:	src/Composerjson/CheckOrAddExtraArray.php
@@ -2,8 +2,11 @@

 namespace Sunnysideup\ModuleChecks\Composerjson;

-use GeneralMethods;
-use UpdateComposer;
+
+
+use Sunnysideup\ModuleChecks\Api\GeneralMethods;
+use Sunnysideup\ModuleChecks\Api\UpdateComposer;
+

 /**
  * sets the default installation folder

modified:	src/Composerjson/UpdataModuleType.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\Composerjson;

-use UpdateComposer;
+
+use Sunnysideup\ModuleChecks\Api\UpdateComposer;
+

 /**
  * sets the default installation folder

modified:	src/ShellCommands/FixPSR2.php
@@ -2,8 +2,11 @@

 namespace Sunnysideup\ModuleChecks\ShellCommands;

-use Director;
-use RunCommandLineMethodOnModule;
+
+
+use SilverStripe\Control\Director;
+use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;
+

 class FixPSR2 extends RunCommandLineMethodOnModule
 {

modified:	src/ShellCommands/SetPermissions.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\ShellCommands;

-use RunCommandLineMethodOnModule;
+
+use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;
+

 class SetPermissions extends RunCommandLineMethodOnModule
 {

modified:	src/ShellCommands/RemoveOrig.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\ShellCommands;

-use RunCommandLineMethodOnModule;
+
+use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;
+

 class RemoveOrig extends RunCommandLineMethodOnModule
 {

modified:	src/ShellCommands/RemoveSVN.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\ShellCommands;

-use RunCommandLineMethodOnModule;
+
+use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;
+

 class RemoveSVN extends RunCommandLineMethodOnModule
 {

modified:	src/ShellCommands/RemoveAPI.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\ShellCommands;

-use RunCommandLineMethodOnModule;
+
+use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;
+

 class RemoveAPI extends RunCommandLineMethodOnModule
 {

modified:	src/ShellCommands/FixConfigBasics.php
@@ -2,7 +2,9 @@

 namespace Sunnysideup\ModuleChecks\ShellCommands;

-use RunCommandLineMethodOnModule;
+
+use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;
+

 class FixConfigBasics extends RunCommandLineMethodOnModule
 {

modified:	src/Objects/ConfigYML.php
@@ -3,10 +3,14 @@
 namespace Sunnysideup\ModuleChecks\Objects;

 use Exception;
-use GeneralMethods;
-use UpdateModules;
-use ViewableData;
+
+
+
 use Yaml;
+use Sunnysideup\ModuleChecks\Api\GeneralMethods;
+use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
+use SilverStripe\View\ViewableData;
+

 /**
  * ### @@@@ START REPLACEMENT @@@@ ###

modified:	src/Objects/ComposerJson.php
@@ -2,10 +2,16 @@

 namespace Sunnysideup\ModuleChecks\Objects;

-use ClassInfo;
-use GeneralMethods;
-use UpdateModules;
-use ViewableData;
+
+
+
+
+use Sunnysideup\ModuleChecks\Api\GeneralMethods;
+use Sunnysideup\ModuleChecks\Api\UpdateComposer;
+use SilverStripe\Core\ClassInfo;
+use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
+use SilverStripe\View\ViewableData;
+

 /**
  * ### @@@@ START REPLACEMENT @@@@ ###
@@ -55,7 +61,7 @@

         if (is_array($this->jsonData)) {
             GeneralMethods::output_to_screen('<li> Updating composer.json </li>');
-            $composerUpdates = ClassInfo::subclassesFor('UpdateComposer');
+            $composerUpdates = ClassInfo::subclassesFor(UpdateComposer::class);

             //remove base class
             array_shift($composerUpdates);

Warnings for src/Objects/ComposerJson.php:
 - src/Objects/ComposerJson.php:72 PhpParser\Node\Expr\Variable
 - WARNING: New class instantiated by a dynamic value on line 72

modified:	src/Objects/GitHubModule.php
@@ -2,13 +2,18 @@

 namespace Sunnysideup\ModuleChecks\Objects;

-use DataObject;
-use Director;
+
+
 use Exception;
 use FileSystem;
-use GeneralMethods;
+
 use GitWrapper;
-use UpdateModules;
+
+use SilverStripe\Control\Director;
+use Sunnysideup\ModuleChecks\Api\GeneralMethods;
+use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
+use SilverStripe\ORM\DataObject;
+

 class GitHubModule extends DataObject
 {

modified:	src/Tasks/ModuleChecks.php
@@ -2,11 +2,18 @@

 namespace Sunnysideup\ModuleChecks\Tasks;

-use BuildTask;
-use Config;
-use DB;
-use GeneralMethods;
-use GitRepoFinder;
+
+
+
+
+
+use Sunnysideup\ModuleChecks\Api\GitRepoFinder;
+use SilverStripe\Core\Config\Config;
+use Sunnysideup\ModuleChecks\Objects\GitHubModule;
+use SilverStripe\ORM\DB;
+use Sunnysideup\ModuleChecks\Api\GeneralMethods;
+use SilverStripe\Dev\BuildTask;
+

 /**
  * check if everything is in plcae for a module
@@ -39,7 +46,7 @@

         $modules = GitRepoFinder::get_all_repos();

-        $gitUser = Config::inst()->get('GitHubModule', 'github_user_name');
+        $gitUser = Config::inst()->get(GitHubModule::class, 'github_user_name');
         $packagistUser = $this->Config()->get('packagist_user_name');

         if ($gitUser && $packagistUser) {
@@ -89,7 +96,7 @@
      */
     protected function hasLicense($name)
     {
-        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get('GitHubModule', 'github_user_name') . '/silverstripe-' . $name . '/master/LICENSE');
+        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get(GitHubModule::class, 'github_user_name') . '/silverstripe-' . $name . '/master/LICENSE');
     }

     /**
@@ -99,7 +106,7 @@
      */
     protected function hasComposerFile($name)
     {
-        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get('GitHubModule', 'github_user_name') . '/silverstripe-' . $name . '/master/composer.json');
+        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get(GitHubModule::class, 'github_user_name') . '/silverstripe-' . $name . '/master/composer.json');
     }

     /**
@@ -109,7 +116,7 @@
      */
     protected function hasReadMeFile($name)
     {
-        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get('GitHubModule', 'github_user_name') . '/silverstripe-' . $name . '/master/README.md');
+        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get(GitHubModule::class, 'github_user_name') . '/silverstripe-' . $name . '/master/README.md');
     }

     protected function existsOnAddOns($name)

modified:	src/Tasks/UpdateModules.php
@@ -2,16 +2,26 @@

 namespace Sunnysideup\ModuleChecks\Tasks;

-use BuildTask;
-use ClassInfo;
-use ComposerJson;
-use ConfigYML;
+
+
+
+
 use Exception;
 use FileSystem;
-use GeneralMethods;
-
-use GitHubModule;
-use GitRepoFinder;
+
+
+
+
+use Sunnysideup\ModuleChecks\Objects\GitHubModule;
+use Sunnysideup\ModuleChecks\Api\GitRepoFinder;
+use Sunnysideup\ModuleChecks\Api\AddFileToModule;
+use SilverStripe\Core\ClassInfo;
+use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;
+use Sunnysideup\ModuleChecks\Api\GeneralMethods;
+use Sunnysideup\ModuleChecks\Objects\ComposerJson;
+use Sunnysideup\ModuleChecks\Objects\ConfigYML;
+use SilverStripe\Dev\BuildTask;
+

 /**
  * main class running all the updates
@@ -76,7 +86,7 @@
         /*
          * Get files to add to modules
          * */
-        $files = ClassInfo::subclassesFor('AddFileToModule');
+        $files = ClassInfo::subclassesFor(AddFileToModule::class);
         array_shift($files);
         $limitedFileClasses = $this->Config()->get('files_to_update');
         if ($limitedFileClasses === []) {
@@ -91,7 +101,7 @@
          * Get commands to run on modules
          * */

-        $commands = ClassInfo::subclassesFor('RunCommandLineMethodOnModule');
+        $commands = ClassInfo::subclassesFor(RunCommandLineMethodOnModule::class);
         array_shift($commands);
         $limitedCommands = $this->Config()->get('commands_to_run');
         if ($limitedCommands === 'none') {

Warnings for src/Tasks/UpdateModules.php:
 - src/Tasks/UpdateModules.php:157 PhpParser\Node\Expr\Variable
 - WARNING: New class instantiated by a dynamic value on line 157

 - src/Tasks/UpdateModules.php:211 PhpParser\Node\Expr\Variable
 - WARNING: New class instantiated by a dynamic value on line 211

 - src/Tasks/UpdateModules.php:220 PhpParser\Node\Expr\Variable
 - WARNING: New class instantiated by a dynamic value on line 220

modified:	_config/database.legacy.yml
@@ -1,36 +1,36 @@
 SilverStripe\ORM\DatabaseAdmin:
   classname_value_remapping:
-    UpdateComposer: Sunnysideup\ModuleChecks\Api\UpdateComposer
-    RunCommandLineMethodOnModule: Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule
-    AddFileToModule: Sunnysideup\ModuleChecks\Api\AddFileToModule
-    GeneralMethods: Sunnysideup\ModuleChecks\Api\GeneralMethods
-    GitRepoFinder: Sunnysideup\ModuleChecks\Api\GitRepoFinder
-    AddGitAttributesToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddGitAttributesToModule
-    AddGitIgnoreToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddGitIgnoreToModule
-    AddHtAccessToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddHtAccessToModule
-    AddTravisYmlToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddTravisYmlToModule
-    AddEditorConfigToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddEditorConfigToModule
-    AddContributingToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddContributingToModule
-    AddUserguideMdToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddUserguideMdToModule
-    AddSourceReadmeToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddSourceReadmeToModule
-    AddLicenceToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddLicenceToModule
-    AddScrutinizerYmlToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddScrutinizerYmlToModule
-    AddChangeLogToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddChangeLogToModule
-    AddTestToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddTestToModule
-    AddManifestExcludeToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddManifestExcludeToModule
-    UpdateLicense: Sunnysideup\ModuleChecks\Composerjson\UpdateLicense
-    UpdataModuleType: Sunnysideup\ModuleChecks\Composerjson\UpdataModuleType
-    CheckOrAddExtraArray: Sunnysideup\ModuleChecks\Composerjson\CheckOrAddExtraArray
-    ModuleConfigInterface: Sunnysideup\ModuleChecks\Interface\ModuleConfigInterface
-    FixPSR2: Sunnysideup\ModuleChecks\ShellCommands\FixPSR2
-    SetPermissions: Sunnysideup\ModuleChecks\ShellCommands\SetPermissions
-    RemoveOrig: Sunnysideup\ModuleChecks\ShellCommands\RemoveOrig
-    RemoveSVN: Sunnysideup\ModuleChecks\ShellCommands\RemoveSVN
-    RemoveAPI: Sunnysideup\ModuleChecks\ShellCommands\RemoveAPI
-    FixConfigBasics: Sunnysideup\ModuleChecks\ShellCommands\FixConfigBasics
-    ConfigYML: Sunnysideup\ModuleChecks\Objects\ConfigYML
-    ComposerJson: Sunnysideup\ModuleChecks\Objects\ComposerJson
-    GitHubModule: Sunnysideup\ModuleChecks\Objects\GitHubModule
-    ModuleChecks: Sunnysideup\ModuleChecks\Tasks\ModuleChecks
-    UpdateModules: Sunnysideup\ModuleChecks\Tasks\UpdateModules
+    Sunnysideup\ModuleChecks\Api\UpdateComposer: Sunnysideup\ModuleChecks\Api\UpdateComposer
+    Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule: Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule
+    Sunnysideup\ModuleChecks\Api\AddFileToModule: Sunnysideup\ModuleChecks\Api\AddFileToModule
+    Sunnysideup\ModuleChecks\Api\GeneralMethods: Sunnysideup\ModuleChecks\Api\GeneralMethods
+    Sunnysideup\ModuleChecks\Api\GitRepoFinder: Sunnysideup\ModuleChecks\Api\GitRepoFinder
+    Sunnysideup\ModuleChecks\FilesToAdd\AddGitAttributesToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddGitAttributesToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddGitIgnoreToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddGitIgnoreToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddHtAccessToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddHtAccessToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddTravisYmlToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddTravisYmlToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddEditorConfigToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddEditorConfigToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddContributingToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddContributingToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddUserguideMdToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddUserguideMdToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddSourceReadmeToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddSourceReadmeToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddLicenceToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddLicenceToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddScrutinizerYmlToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddScrutinizerYmlToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddChangeLogToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddChangeLogToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddTestToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddTestToModule
+    Sunnysideup\ModuleChecks\FilesToAdd\AddManifestExcludeToModule: Sunnysideup\ModuleChecks\FilesToAdd\AddManifestExcludeToModule
+    Sunnysideup\ModuleChecks\Composerjson\UpdateLicense: Sunnysideup\ModuleChecks\Composerjson\UpdateLicense
+    Sunnysideup\ModuleChecks\Composerjson\UpdataModuleType: Sunnysideup\ModuleChecks\Composerjson\UpdataModuleType
+    Sunnysideup\ModuleChecks\Composerjson\CheckOrAddExtraArray: Sunnysideup\ModuleChecks\Composerjson\CheckOrAddExtraArray
+    Sunnysideup\ModuleChecks\Interface\ModuleConfigInterface: Sunnysideup\ModuleChecks\Interface\ModuleConfigInterface
+    Sunnysideup\ModuleChecks\ShellCommands\FixPSR2: Sunnysideup\ModuleChecks\ShellCommands\FixPSR2
+    Sunnysideup\ModuleChecks\ShellCommands\SetPermissions: Sunnysideup\ModuleChecks\ShellCommands\SetPermissions
+    Sunnysideup\ModuleChecks\ShellCommands\RemoveOrig: Sunnysideup\ModuleChecks\ShellCommands\RemoveOrig
+    Sunnysideup\ModuleChecks\ShellCommands\RemoveSVN: Sunnysideup\ModuleChecks\ShellCommands\RemoveSVN
+    Sunnysideup\ModuleChecks\ShellCommands\RemoveAPI: Sunnysideup\ModuleChecks\ShellCommands\RemoveAPI
+    Sunnysideup\ModuleChecks\ShellCommands\FixConfigBasics: Sunnysideup\ModuleChecks\ShellCommands\FixConfigBasics
+    Sunnysideup\ModuleChecks\Objects\ConfigYML: Sunnysideup\ModuleChecks\Objects\ConfigYML
+    Sunnysideup\ModuleChecks\Objects\ComposerJson: Sunnysideup\ModuleChecks\Objects\ComposerJson
+    Sunnysideup\ModuleChecks\Objects\GitHubModule: Sunnysideup\ModuleChecks\Objects\GitHubModule
+    Sunnysideup\ModuleChecks\Tasks\ModuleChecks: Sunnysideup\ModuleChecks\Tasks\ModuleChecks
+    Sunnysideup\ModuleChecks\Tasks\UpdateModules: Sunnysideup\ModuleChecks\Tasks\UpdateModules


Writing changes for 36 files
✔✔✔