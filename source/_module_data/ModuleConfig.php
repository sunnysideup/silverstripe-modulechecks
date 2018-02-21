<?php


class ModuleConfig implements ModuleConfigInterface
{

    /**
     * @return array
     */
    public function params()
    {
        return array();
    }

    /**
     * return null | string
     */
    public function customiseFile($location, $fileContent)
    {
        return null;
    }
}
