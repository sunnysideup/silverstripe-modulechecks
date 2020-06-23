<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;

use SilverStripe\Core\Config\Config;
use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\Flush\FlushNow;

class UpdateTag extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run(): bool
    {
        $outcome = true;
        $tagDelayString = Config::inst()->get(BaseObject::class, 'tag_delay');
        if (! $tagDelayString) {
            $tagDelayString = '-3 weeks';
        }

        $tagDelay = strtotime($tagDelayString);
        if (! $tagDelay) {
            $tagDelay = strtotime('-3 weeks');
        }

        $tag = $this->repo->getLatestTag();

        $commitTime = $this->repo->getLatestCommitTime();

        if (! $commitTime) { // if no commits, cannot create a tag
            return false;
        }

        $newTagString = '';

        if (! $tag) {
            $newTagString = '1.0.0';
        } elseif ($tag && $commitTime > $tag['timestamp'] && $commitTime < $tagDelay) {
            $changeType = $this->repo->getChangeTypeSinceLastTag();

            $newTagString = $this->repo->findNextTag($tag, $changeType);
        }

        if ($newTagString) {
            FlushNow::do_flush('Creating new tag  ' . $newTagString . ' ...');

            //git tag -a 0.0.1 -m "testing tag"
            $options = [
                'a' => $newTagString,
                'm' => Config::inst()->get(BaseObject::class, 'tag_create_message'),
            ];

            $outcome = $this->repo->createTag($options);
        }
        if( ! $outcome) {
            $this->logError('Could not create tag');
        }
        return $this->hasError();
    }

    public function getDescription(): string
    {
        return 'Add automated tag';
    }
}
