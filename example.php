<?php

use Manialib\Maniascript\Autoloader;
use Manialib\Maniascript\Compiler;

require_once 'vendor/autoload.php';

$compiler = new Compiler(new Autoloader());

echo $compiler->compile('Manialib/Http.Script.txt');
