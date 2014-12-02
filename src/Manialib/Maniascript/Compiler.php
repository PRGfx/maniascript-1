<?php

namespace Manialib\Maniascript;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Compiler implements CompilerInterface
{

    use \Psr\Log\LoggerAwareTrait;
    /**
     * @var Autoloader
     */
    protected $autoloader;
    protected $compilerFilters   = [];
    protected $compiledLibraries = [];
    protected $includedLibraries = [];
    protected $ignoredLibraries  = [
        "TextLib",
        "MathLib",
    ];

    protected function virtualIncludeFilter($library, $maniascript)
    {
        $includeRegexp = '%#Include +"([A-Za-z0-9_/\.-]+)" +as +([A-Za-z0-9_]+)%m';
        preg_match_all($includeRegexp, $maniascript, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            // Include once inline
            $includedLibrary = $match[1];
            if (!in_array($includedLibrary, $this->ignoredLibraries)) {
                $includedManiascript = $this->compile($includedLibrary);
            } else {
                $includedManiascript = '';
                $includedLibrary     = $includedLibrary.'::';
            }
            $maniascript = preg_replace('%'.$match[0].'%', $includedManiascript, $maniascript);

            // Understand Maniascript namespace
            $namespace = str_replace(["/", ".Script.txt"], ["_", "_"], $includedLibrary);

            // Replace Alias with namespace
            $maniascript = preg_replace('%([^A-Za-z0-9_]+)('.$match[2].'::)%', '$1'.$namespace, $maniascript);
        }
        return $maniascript;
    }

    function __construct(Autoloader $autloader, LoggerInterface $logger = null)
    {
        $this->autoloader = $autloader;
        $this->logger     = $logger ? : new NullLogger();

        $this->compilerFilters = [
            [$this, 'virtualIncludeFilter'],
        ];
    }

    function addCompilerFilter($filter)
    {
        $this->compilerFilters[] = $filter;
    }

    function compile($library)
    {
        if (!$library) {
            throw new Exception('wat?');
        }
        $maniascript = $this->autoloader->autoloadOnce($library);
        if ($maniascript) {
            foreach ($this->compilerFilters as $callback) {
                $maniascript = call_user_func($callback, $library, $maniascript);
            }
        }
        return $maniascript;
    }
}
