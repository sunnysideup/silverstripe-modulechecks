<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;
use Sunnysideup\ModuleChecks\Commands\OtherCommandsAbstract;
use Sunnysideup\ModuleChecks\Api\Scrutinizer;
use Sunnysideup\ModuleChecks\BaseObject;
use SilverStripe\Core\Config\Config;

class UpdateTag extends ChecksAbstract
{

    private function run() : bool
    {
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
            $changeType = $moduleObject->getChangeTypeSinceLastTag();

            $newTagString = $this->findNextTag($tag, $changeType);
        }

        if ($newTagString) {
            GeneralMethods::output_to_screen('<li> Creating new tag  ' . $newTagString . ' ... </li>');

            //git tag -a 0.0.1 -m "testing tag"
            $options = [
                'a' => $newTagString,
                'm' => Config::inst()->get(BaseObject::class, 'tag_create_message'),
            ];

            $this->repo->createTag($options);
        }

        return true;
    }

    protected function findNextTag(array $tag, string $changeType) : string
    {
        switch ($changeType) {
            case 'MAJOR':
                $tag['tagparts'][0] = intval($tag['tagparts'][0]) + 1;
                $tag['tagparts'][1] = 0;
                $tag['tagparts'][2] = 0;
                break;

            case 'MINOR':
                $tag['tagparts'][1] = intval($tag['tagparts'][1]) + 1;
                $tag['tagparts'][2] = 0;
                break;

            default:
                case 'PATCH':
                $tag['tagparts'][2] = intval($tag['tagparts'][2]) + 1;
                break;
        }

        return trim(implode('.', $tag['tagparts']));
    }

}
