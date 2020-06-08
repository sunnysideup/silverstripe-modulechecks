<?php

class ComposerAddKeywords extends UpdateComposer
{
    protected $defaultWords = array(
        'Silverstripe',
        'CMS',
        'Silverstripe-CMS'
    );

    private function removeKeyWords($array)
    {
        $json = $this->composerJsonObj->getJsonData();
        $clean = true;
        if (is_array($json['keywords'])) {
            foreach ($array as $word) {
                $index = array_search(strtolower($word), array_map('strtolower', $json['keywords']));

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

    public function run()
    {
        $this->removeKeyWords($this->defaultWords);

        $json = $this->composerJsonObj->getJsonData();

        if (!is_array($json['keywords'])) {
            $json['keywords'] = array();
        }

        foreach ($this->defaultWords as $word) {
            array_unshift($json['keywords'], $word);
        }

        $json = $this->composerJsonObj->setJsonData($json);
    }
}
