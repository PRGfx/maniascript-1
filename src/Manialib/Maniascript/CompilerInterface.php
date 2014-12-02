<?php

namespace Manialib\Maniascript;

interface CompilerInterface
{

    public function __construct(Autoloader $autloader, \Psr\Log\LoggerInterface $logger);

    /**
     * Compile the library and its includes as a single script.
     *
     * @param string $library
     * @return string
     * @throws LibraryNotFoundException
     */
    public function compile($library);
}
