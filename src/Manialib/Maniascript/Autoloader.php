<?php

namespace Manialib\Maniascript;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

class Autoloader implements AutoloaderInterface
{

    use \Psr\Log\LoggerAwareTrait;
    const NAMESPACE_INCLUDE_SEPARATOR = '/';
    const NAMESPACE_SCRIPT_SEPARATOR  = '_';

    protected $includePaths = array();
    protected $libraries    = array();

    /**
     * @deprecated
     */
    protected $autoloadedLibrariesFilenames = array();

    /**
     * @deprecated
     */
    protected $autoloadedLibrariesManiascripts = array();

    /**
     * @return Library
     * @throws LibraryNotFoundException
     */
    protected function doAutoload($library)
    {
        if (array_key_exists($library, $this->libraries)) {
            return $this->libraries[$library];
        } else {
            $filename = implode(DIRECTORY_SEPARATOR, explode(self::NAMESPACE_INCLUDE_SEPARATOR, $library));
            foreach ($this->includePaths as $includePath) {
                if (file_exists($includePath.DIRECTORY_SEPARATOR.$filename)) {
                    $lib                       = new Library($library, $includePath.DIRECTORY_SEPARATOR.$filename);
                    $this->libraries[$library] = $lib;
                    return $lib;
                }
            }
        }
        throw new LibraryNotFoundException($library);
    }

    function __construct(LoggerInterface $logger = null)
    {
        $this->logger       = $logger ? : new NullLogger();
        $this->includePaths = [ __DIR__.'/Resources/maniascript'];
    }

    function addIncludePath($path)
    {
        $this->includePaths[] = $path;
    }

    public function exists($library)
    {
        try {
            $this->doAutoload($library);
            return true;
        } catch (LibraryNotFoundException $e) {
            return false;
        }
    }

    function autoload($library)
    {
        $lib = $this->doAutoload($library);
        $lib->setAutoloaded(true);
        return $lib->getContents();
    }

    function autoloadOnce($library)
    {
        $lib = $this->doAutoload($library);
        $maniascript = $lib->autoloaded() ? '' : $lib->getContents();
        $lib->setAutoloaded(true);
        return $maniascript;
    }
}
