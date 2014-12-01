<?php

namespace Manialib\Maniascript;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Compiler
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

    protected function addHeaderFilter($library, $maniascript)
    {
        return "//Autoloaded: $library\n\n$maniascript\n";
    }

    protected function virtualIncludeFilter($library, $maniascript)
    {
        $includeRegexp = '%#Include +"([A-Za-z0-9_/\.-]+)" +as +([A-Za-z0-9_]+)%m';
        preg_match_all($includeRegexp, $maniascript, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            // Include once inline
            $includedLibrary = $match[1];
            if (in_array($includedLibrary, $this->ignoredLibraries)) {
                continue;
            }
            if (!in_array($includedLibrary, $this->includedLibraries)) {
                $includedManiascript       = $this->compile($includedLibrary);
                $this->includedLibraries[] = $includedLibrary;
            } else {
                $includedManiascript = '//Not autoloaded: already included';
            }
            $maniascript = preg_replace('%'.$match[0].'%', '//'.$match[0]."\n".$includedManiascript, $maniascript);

            // Understand Maniascript namespace
            $namespace = str_replace(["/", ".Script.txt"], ["_", "_"], $match[1]);

            // Replace Alias with namespace
            $maniascript = preg_replace('%([^A-Za-z0-9_]*)('.$match[2].'::)%', '$1'.$namespace, $maniascript);
        }
        return $maniascript;
    }

    function __construct(Autoloader $autloader, LoggerInterface $logger = null)
    {
        $this->autoloader = $autloader;
        $this->logger     = $logger ? : new NullLogger();

        $this->compilerFilters = [
            [$this, 'addHeaderFilter'],
            [$this, 'virtualIncludeFilter'],
        ];
    }

    function addCompilerFilter($filter)
    {
        $this->compilerFilters[] = $filter;
    }

    function compile($library)
    {
        if (!array_key_exists($library, $this->compiledLibraries)) {
            $maniascript = $this->autoloader->autoload($library);
            foreach ($this->compilerFilters as $callback) {
                $maniascript = call_user_func($callback, $library, $maniascript);
            }
            $this->compiledLibraries[$library] = $maniascript;
        }
        return $this->compiledLibraries[$library];
    }
}
