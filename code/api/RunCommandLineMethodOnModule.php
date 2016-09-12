<?php

abstract class RunCommandLineMethodOnModule extends Object
{

      /**
       * root dir for module
       * e.g. /var/www/modules/mymodule
       * no final slash
       *
       * @var string
       */
      protected $rootDirForModule = '';

      /**
       *
       * @var string
       */
      protected $command = '';

      function setRootDirForModule($rootDirForModule)
      {
          $this->$rootDirForModule = $rootDirForModule;
      }

      /**
       *
       *
       * @param string
       */
      function setCommand($command)
      {
          $this->command = $command;
      }

      public function __construct($rootDirForModule = ''){
          $this->rootDirForModule = $rootDirForModule;
      }

      function run() {
          if( ! $this->rootDirForModule) {
              user_error('no root dir for module has been set');
          }
          if( ! $this->command) {
              user_error('command not set');
          }
          $this->runCommand();
      }

      /**
       * runs a command from the root dir or the module
       */
      protected function runCommand()
      {
          GeneralMethods::outputToScreen('Running' . $this->command);
          return exec(
              ' cd '.$this->rootDirForModule.';
                '.$this->command.'
                '
          );
      }
}
