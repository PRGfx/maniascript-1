<?php

namespace Manialib\Maniascript;

class LibraryNotFoundException extends \Exception
{

    function __construct($library)
    {
        parent::__construct(sprintf('Maniascript library "%s" not found', $library));
    }
}
