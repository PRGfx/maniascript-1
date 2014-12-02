# Manialib\Maniascript

> **Unstable!**

Standalone PHP component to help with developing a Maniascript application. This is part of [Manialib](https://github.com/manialib/manialib).

The goal is to help writing single-file Maniascript applications using practices similar to game modes, editor plugins, etc. Specifically, using `#Include XX as YY` directives and autoloading `XX` from a predefined set of paths.

## Autoloader

`Manialib\Maniascript\Autoloader` provides a way to automatically load a Maniascript file, given it's library name (eg. "Manialib/Logger.Script.txt") and a set of include paths. It's inspired from [PHP's PSR-4](http://www.php-fig.org/psr/psr-4/).

See [Manialib\Maniascript\AutoloaderInterface](https://github.com/manialib/maniascript/blob/master/src/Manialib/Maniascript/AutoloaderInterface.php) for reference.

## Compiler

`Manialib\Maniascript\Compiler` helps partitioning a Maniascript application in several files when you cannot use normal #Include directives (eg. when writing a Maniascript application for the Manialink browser). It will replace the "include as" directives recursively, eventually providing a single script.

## Libraries

*todo*

## Limitations

Right now, inclued libaries must be written with "full namespaces", compared to standard #Includes where you only define the function name, not the fully qualified function name.

Eg. if you're using `#Include "Manialib/Logger.Script.txt" as Logger` and `Logger::Info()`, the `Info()` function in the Logger library will be defined as `Manialib_Logger_Info()` whereas with standard #Includes it would be defined as `Info()`.

## Example

See [/example](https://github.com/manialib/maniascript/blob/master/example)