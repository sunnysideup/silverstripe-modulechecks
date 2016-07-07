## Silverstripe Module Checks ##

Allows you to add/update standards files in modules.

It also allows you to check a bunch of
very basic requirements for your Silverstripe Modules
such as whether it is listed on packagist.

# Configuration

You can either run it for all the modules for a github account
or you can specify specific modules in your `yml` configurations 
(`mysite/_config/modulecheck.yml`).

You can either run it for all files fon a github account or one specific file. Again, 
you can specify specific files in your `yml` configurations 
(`mysite/_config/modulecheck.yml`).

Thirdly, you can set exceptions / custom configs in a file within each module.

you can place any custom configs in the following file: 

`/ssmoduleconfig/ModuleConfig.php`

dont forget to also add a `/ssmoduleconfig/_manifest_exclude` file.

_IMPORTANT_
For a module to be included in the processs, it needs to have the 
`/ssmoduleconfig/ModuleConfig.php` file. 


## Installation Instructions ##

Install like any
other Silverstripe Module module.

To use the module, set the configs in the task and
then browse to /dev/tasks/ModuleChecks to run ...



## Developers ##

Nicolaas Francken [at] sunnysideup.co.nz
