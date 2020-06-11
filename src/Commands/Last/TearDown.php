<?php
use Sunnysideup\ModuleChecks\Commands\LastAbstract;

class TearDown extends LastAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run(): bool
    {
        if (! $this->repo->add()) {
            $this->logError('Could not add files module to Repo');
            return false;
        }
        if (! $this->repo->commit()) {
            $this->logError('Could not commit files to Repo');
            return false;
        }

        if (! $this->repo->push()) {
            $this->logError('Could not push files to Repo');
            return false;
        }
        if (! $this->repo->removeClone()) {
            $this->logError('Could not remove local copy of repo');
            return false;
        }

        return $this->hasError();
    }

    public function getDescription(): string
    {
        return 'Git Add, Git Commit, Git Push and Delete Directory';
    }
}
