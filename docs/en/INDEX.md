## Silverstripe Module Checks ##

Allows you to add/update standards files in modules.

It also allows you to check a bunch of
very basic requirements for your Silverstripe Modules
such as whether it is listed on packagist.

# Configuration

You can either run it for all the modules for a github account
or you can specify specific modules in your `yml` configurations
(`app/_config/modulecheck.yml`).

You can either run it for all files fon a github account or one specific file. Again,
you can specify specific files in your `yml` configurations
(`app/_config/modulecheck.yml`).

Thirdly, you can set exceptions / custom configs in a file within each module. You can place any custom configs in the following file:

`/_module_data/ModuleConfig.php`

dont forget to also add a `/_module_data/_manifest_exclude` file.

_IMPORTANT_
For a module to be included in the processs, it needs to have the
`/_module_data/ModuleConfig.php` file.


## Installation Instructions ##

Install like any
other Silverstripe Module module.

You will need to configure the following values in config.yml
for the UpdateModules task to run:

  github_user_name - your git user name
  github_account_base_url - the base url

  temp_folder_name - temporary folder for modules to be cloned into - must be writeable by www-data
  path_to_private_key: path to id_rsa file, for example, /var/www/.ssh/id_rsa
  The known_hosts file also needs to be created.

CAUTION: The readme file for that this module will write is not customised
and will overwrite any customised readme content that exists. Use the
files_to_update in the config to adjust the files that get updated,


A temporary folder for working with the module also needs
to be created and setup in the config. e.g. /var/www/temp_modules
The module will not run without a temporary folder, or
if it contains any files. The tempoarary folder needs to be writable
by www-data

To use the module, set the configs in the task and
then browse to /dev/tasks/ModuleChecks to run ...

To install PHPDOX (check for latest version!)

```
    wget https://github.com/theseer/phpdox/releases/download/0.9.0/phpdox-0.9.0.phar
    chmod +x phpdox-0.9.0.phar
    sudo mv phpdox-0.9.0.phar /usr/bin/phpdox
```

For PHPDOX, you may also need to install/enable XSL module:

```
    sudo apt-get install php7-xsl
    sudo php7enmod xsl
    sudo service apache2 restart
```
