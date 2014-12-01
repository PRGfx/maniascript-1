<?php

namespace Manialib\Maniascript;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

class Autoloader
{

    use \Psr\Log\LoggerAwareTrait;
    const NAMESPACE_INCLUDE_SEPARATOR = '/';
    const NAMESPACE_SCRIPT_SEPARATOR  = '_';

    protected $includePaths                    = array();
    protected $autoloadedLibrariesFilenames    = array();
    protected $autoloadedLibrariesManiascripts = array();

    protected function exists($library)
    {
        if (!array_key_exists($library, $this->autoloadedLibrariesFilenames)) {
            $filename = implode(DIRECTORY_SEPARATOR, explode(self::NAMESPACE_INCLUDE_SEPARATOR, $library));

            foreach ($this->includePaths as $includePath) {
                if (file_exists($includePath.DIRECTORY_SEPARATOR.$filename)) {
                    $this->autoloadedLibrariesFilenames[$library] = $includePath.DIRECTORY_SEPARATOR.$filename;
                    return $this->autoloadedLibrariesFilenames[$library];
                }
            }
        }
        return false;
    }

    protected function getManiascript($library)
    {
        if (!array_key_exists($library, $this->autoloadedLibrariesManiascripts)) {
            $filename = $this->exists($library);
            if (!$filename) {
                throw new RuntimeException(sprintf('Maniascript library "%s" not found.', $library));
            }
            $this->logger->info('Autoloaded Maniascript library: '.$library);
            $this->autoloadedLibrariesManiascripts[$library] = file_get_contents($filename);
        }
        return $this->autoloadedLibrariesManiascripts[$library];
    }

    function __construct(LoggerInterface $logger = null)
    {
        $this->logger      = $logger ? : new NullLogger();
        $this->includePaths = [ __DIR__.'/Resources/maniascript'];
    }

    function addIncludePath($path)
    {
        $this->includePaths[] = $path;
    }

    function autoload($library)
    {
        return $this->getManiascript($library);
    }
}
