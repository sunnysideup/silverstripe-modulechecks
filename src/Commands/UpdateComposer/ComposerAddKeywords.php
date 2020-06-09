<?php

namespace Sunnysideup\ModuleChecks\Commands\UpdateComposer;

use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;

class ComposerAddKeywords extends UpdateComposerAbstract
{
    protected $defaultWords = [
        'Silverstripe',
        'CMS',
        'Silverstripe-CMS',
    ];

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run()
    {
        $this->removeKeyWords($this->defaultWords);

        $json = $this->composerJsonObj->getJsonData();

        if (! is_array($json['keywords'])) {
            $json['keywords'] = [];
        }

        foreach ($this->defaultWords as $word) {
            array_unshift($json['keywords'], $word);
        }

        $json = $this->composerJsonObj->setJsonData($json);
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Add basic keywords (e.g. Silverstripe) to composer file';
    }

    private function removeKeyWords($array)
    {
        $json = $this->composerJsonObj->getJsonData();
        $clean = true;
        if (is_array($json['keywords'])) {
            foreach ($array as $word) {
                $index = array_search(strtolower($word), array_map('strtolower', $json['keywords']), true);

                if ($index !== false) {
                    $clean = false;
                    unset($json['keywords'][$index]);
                }
            }
            if (! $clean) {
                //update ...
                $this->composerJsonObj->setJsonData($json);
                //run again ...
                $this->removeKeyWords($array);
            }
        }
        return $clean;
    }
}
