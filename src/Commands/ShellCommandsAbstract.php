<?php

namespace Sunnysideup\ModuleChecks\Commands;
use Sunnysideup\Flush\FlushNow;

abstract class ShellCommandsAbstract extends BaseCommand
{
    /**
     * root dir for module
     * e.g. /var/www/modules/mymodule
     * no final slash
     *
     * @var string
     */
    protected $repo = '';

    protected $commands = [];

    protected $outcomes = [];

    /**
     * @var string
     */
    protected $rootDirForModule = '';

    private static $enabled = false;

    public function __construct(? Module $repo = null)
    {
        parent::__construct($repo);
        if($this->repo) {
            $this->rootDirForModule = $this->repo->Directory();
        }
    }

    public function setRootDirForModule($rootDirForModule)
    {
        $this->{$rootDirForModule} = $rootDirForModule;
    }

    /**
     * @param string $commands
     */
    public function setCommand(array $commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @param string $command
     */
    public function addCommands(string $command)
    {
        $this->commands[] = $command;

        return $this;
    }

    public function run(): bool
    {
        if (! $this->rootDirForModule) {
            user_error('no root dir for module has been set');
        }
        if (! count($this->commands)) {
            user_error('command not set');
        }
        return $this->runCommand();
    }

    abstract public function getDescription(): string;

    public static function CheckCommandExists($cmd)
    {
        return ! empty(shell_exec("which ${cmd}"));
    }

    public function getError(): string
    {
        return print_r($this->outcomes, 1);
    }

    /**
     * runs a command from the root dir or the module
     */
    protected function runCommand()
    {
        foreach ($this->commands as $command) {
            self::flushNow('Running ' . $command);
            $obj = PHP2CommandLineSingleton::create();
            $results = $obj->exec(
                $this->rootDirForModule, //dir ...
                $command, //command
                'Running ' . $command, //comment
                true //run immediately?
            );
            foreach ($results as $result) {
                $this->outcomes[] = $result;
            }
        }
        return true;
    }
}
