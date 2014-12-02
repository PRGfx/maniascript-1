<?php

use Manialib\Maniascript\Autoloader;
use Manialib\Maniascript\Compiler;

require_once __DIR__.'/../vendor/autoload.php';

$autoloader = new Autoloader();
$autoloader->addIncludePath(__DIR__.'/src1');
$autoloader->addIncludePath(__DIR__.'/src2');

$compiler = new Compiler($autoloader);

echo $compiler->compile('Hello.Script.txt');

/** Will echo:

Void Foo_Bar_BarFunction() {
    TextLib::ToText(1);
    return;
}


Void Bar_Foo_FooFunction() {
    TextLib::ToText(1);
    return;
}

Void HelloFunction() {
    Foo_Bar_BarFunction();
    Bar_Foo_FooFunction();
}

*/