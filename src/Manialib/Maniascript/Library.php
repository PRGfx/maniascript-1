<?php

namespace Manialib\Maniascript;

class Library
{
    protected $name;
    protected $filename;
    protected $contents;
    protected $autoloaded;

    function __construct($name, $filename)
    {
        $this->name     = $name;
        $this->filename = $filename;
    }

    function getName()
    {
        return $this->name;
    }

    function getFilename()
    {
        return $this->filename;
    }

    function getContents()
    {
        if (!$this->contents) {
            $this->contents = file_get_contents($this->filename);
        }
        return $this->contents;
    }

    function autoload($library)
    {
        return $this->doAutoload($library)->getContents();
    }

    function autoloaded()
    {
        return $this->autoloaded;
    }

    function setAutoloaded($autoloaded)
    {
        $this->autoloaded = $autoloaded;
    }
}
