<?php

class SetupDirectoryAndClone extends FirstAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run(): bool
    {
        if (! $this->repo->getDirectory()) {
            $this->logError('Could not set up directory');
            die('Please make sure that directory can be set up');
        }
        $this->repo->checkOrSetGitCommsWrapper($forceNew = true);

        return $this->hasError();
    }

    public function getDescription(): string
    {
        return 'Set up directory and checkout repo';
    }
}
