<?php

namespace Manialib\Maniascript;

use Psr\Log\LoggerInterface;

interface AutoloaderInterface
{

    /**
     * Creates the autoloader
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null);

    /**
     * Adds a path to look for Maniascript libraries
     *
     * @param string $path
     * @return void
     */
    public function addIncludePath($path);

    /**
     * Checks if the library exists
     *
     * @param string $library
     * @return bool
     */
    public function exists($library);

    /**
     * Returns the Maniascript code of the library
     *
     * @param string $library
     * @return string
     * @throws LibraryNotFoundException
     */
    public function autoload($library);

    /**
     * Returns the Maniascript code of the library, but only once. Subsequent calls will return an empty string.
     *
     * @param string $library
     * @return string
     * @throws LibraryNotFoundException
     */
    public function autoloadOnce($library);
}
