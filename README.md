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

<?php

Let's compile [Manialib/Http.Script.txt](https://github.com/manialib/maniascript/blob/master/src/Manialib/Maniascript/Resources/maniascript/Manialib/Http.Script.txt), which is declaring `#Include "Manialib/Logger.Script.txt" as Logger` and `#Include "Manialib/Event.Script.txt" as Event`:

```
use Manialib\Maniascript\Autoloader;
use Manialib\Maniascript\Compiler;

require_once 'vendor/autoload.php';

$compiler = new Compiler(new Autoloader());

echo $compiler->compile('Manialib/Http.Script.txt');
```

Will print:

```
// Log levels as defined in RFC 5424 http://tools.ietf.org/html/rfc5424
#Const C_Manialib_Logger_Level_Debug     100
#Const C_Manialib_Logger_Level_Info      200
#Const C_Manialib_Logger_Level_Notice    250
#Const C_Manialib_Logger_Level_Warning   300
#Const C_Manialib_Logger_Level_Error     400
#Const C_Manialib_Logger_Level_Critical  500
#Const C_Manialib_Logger_Level_Alert     550
#Const C_Manialib_Logger_Level_Emergency 600

declare Boolean[Integer] Private_Manialib_Logger_Config;

Text Private_Manialib_Logger_GetLevelName(Integer Level) {
    switch(Level) {
        case C_Manialib_Logger_Level_Debug: return "Debug";
        case C_Manialib_Logger_Level_Info: return "Info";
        case C_Manialib_Logger_Level_Notice: return "Notice";
        case C_Manialib_Logger_Level_Warning: return "Warning"; 
        case C_Manialib_Logger_Level_Error: return "Error";
        case C_Manialib_Logger_Level_Critical: return "Critical";
        case C_Manialib_Logger_Level_Alert: return "Alert";
        case C_Manialib_Logger_Level_Emergency: return "Emergency";
    }
    return "Unkown Log Level";
}

Void Manialib_Logger_SetLevelConfig(Integer Level, Boolean IsVisible) {
    Private_Manialib_Logger_Config[Level] = IsVisible;
}

Void Manialib_Logger_Log(Integer Level, Text Message) {
    if(Private_Manialib_Logger_Config.existskey(Level) && Private_Manialib_Logger_Config[Level] == False) {
        return;
    }
    log("["^Private_Manialib_Logger_GetLevelName(Level)^"] "^Message);
}

Void Manialib_Logger_Debug(Text Message) {
    Manialib_Logger_Log(C_Manialib_Logger_Level_Debug, Message);
}

Void Manialib_Logger_Info(Text Message) {
    Manialib_Logger_Log(C_Manialib_Logger_Level_Info, Message);
}

// todo: implement other levels...
// Log levels as defined in RFC 5424 http://tools.ietf.org/html/rfc5424
#Const C_Manialib_Logger_Level_Debug     100
#Const C_Manialib_Logger_Level_Info      200
#Const C_Manialib_Logger_Level_Notice    250
#Const C_Manialib_Logger_Level_Warning   300
#Const C_Manialib_Logger_Level_Error     400
#Const C_Manialib_Logger_Level_Critical  500
#Const C_Manialib_Logger_Level_Alert     550
#Const C_Manialib_Logger_Level_Emergency 600

declare Boolean[Integer] Private_Manialib_Logger_Config;

Text Private_Manialib_Logger_GetLevelName(Integer Level) {
    switch(Level) {
        case C_Manialib_Logger_Level_Debug: return "Debug";
        case C_Manialib_Logger_Level_Info: return "Info";
        case C_Manialib_Logger_Level_Notice: return "Notice";
        case C_Manialib_Logger_Level_Warning: return "Warning"; 
        case C_Manialib_Logger_Level_Error: return "Error";
        case C_Manialib_Logger_Level_Critical: return "Critical";
        case C_Manialib_Logger_Level_Alert: return "Alert";
        case C_Manialib_Logger_Level_Emergency: return "Emergency";
    }
    return "Unkown Log Level";
}

Void Manialib_Logger_SetLevelConfig(Integer Level, Boolean IsVisible) {
    Private_Manialib_Logger_Config[Level] = IsVisible;
}

Void Manialib_Logger_Log(Integer Level, Text Message) {
    if(Private_Manialib_Logger_Config.existskey(Level) && Private_Manialib_Logger_Config[Level] == False) {
        return;
    }
    log("["^Private_Manialib_Logger_GetLevelName(Level)^"] "^Message);
}

Void Manialib_Logger_Debug(Text Message) {
    Manialib_Logger_Log(C_Manialib_Logger_Level_Debug, Message);
}

Void Manialib_Logger_Info(Text Message) {
    Manialib_Logger_Log(C_Manialib_Logger_Level_Info, Message);
}

// todo: implement other levels...

declare Text[][][] Private_Manialib_Event_PendingEvents;
declare Text[][][] Private_Manialib_Event_UpcomingEvents;

Void Manialib_Event_DispatchCustomEvent(Text _Type, Text[] _Data) {
    if(_Data.count > 0)
    {
        Manialib_Logger_Debug("[Event] "^_Type^" "^_Data[0]);
    }
    else
    {
        Manialib_Logger_Debug("[Event] "^_Type);
    }
  Private_Manialib_Event_UpcomingEvents.add([[_Type], _Data]);
}

Void Manialib_Event_DispatchCustomEvent(Text _Type) {
  Manialib_Event_DispatchCustomEvent(_Type, Text[]);
}

Text[][][] Manialib_Event_PendingEvents() {
  return Private_Manialib_Event_PendingEvents;
}

Void Manialib_Event_Yield() {
    Private_Manialib_Event_PendingEvents = Private_Manialib_Event_UpcomingEvents;
  if(Private_Manialib_Event_UpcomingEvents.count > 0) {
    Private_Manialib_Event_UpcomingEvents = Text[][][];
  }
}

declare Ident[Ident] G_Manialib_Http_Requests;
declare Text[Ident] G_Manialib_Http_RequestsResultsSuccess;
declare Text[Ident] G_Manialib_Http_RequestsResultsError;
declare Integer[Ident] G_Manialib_Http_RequestsExec;

Ident Manialib_Http_PostAsync(Text _Url, Text _Data) {
  declare Request <=> Http.CreatePost(_Url, _Data);
  if (Request != Null) {
    G_Manialib_Http_Requests[Request.Id] = Request.Id;
    G_Manialib_Http_RequestsExec[Request.Id] = Now;
  }
  Manialib_Logger_Info("[Http] "^Request.Id^" POST "^_Url);
  return Request.Id;
}

Ident Manialib_Http_GetAsync(Text _Url) {
  declare Request <=> Http.CreateGet(_Url, False);
  if (Request != Null) {
    G_Manialib_Http_Requests[Request.Id] = Request.Id;
    G_Manialib_Http_RequestsExec[Request.Id] = Now;
  }
  Manialib_Logger_Info("[Http] "^Request.Id^" GET "^_Url);
  return Request.Id;
}

Void Private_Manialib_Http_HandleFinishedRequests() {
    declare Ident[] ToRemove;
    foreach (Request in Http.Requests) {
        if (!G_Manialib_Http_Requests.existskey(Request.Id)) continue; //Ignore other requests
        if (Request.IsCompleted) { 
            G_Manialib_Http_RequestsExec[Request.Id] = Now - G_Manialib_Http_RequestsExec[Request.Id];
            if (Request.StatusCode == 200) {
                G_Manialib_Http_RequestsResultsSuccess[Request.Id] = Request.Result;
                Manialib_Logger_Info("[Http] "^Request.Id^" OK 200 in "^G_Manialib_Http_RequestsExec[Request.Id]^"ms");
                Manialib_Event_DispatchCustomEvent("Manialib.Http.Success", [""^Request.Id, Request.Result]);
            } else {
                G_Manialib_Http_RequestsResultsError[Request.Id] = Request.Result;
                Manialib_Logger_Info("[Http] "^Request.Id^" ERROR "^Request.StatusCode^" in "^G_Manialib_Http_RequestsExec[Request.Id]^"ms");
                Manialib_Event_DispatchCustomEvent("Manialib.Http.Error", [""^Request.Id, Request.Result, ""^G_Manialib_Http_RequestsExec[Request.Id]]);
            }
            ToRemove.add(Request.Id);
        }
    }
    foreach (RequestId in ToRemove) {
        G_Manialib_Http_Requests.removekey(RequestId);
        G_Manialib_Http_RequestsExec.removekey(RequestId);
        Http.Destroy(Http.Requests[RequestId]);
    }
}

Void Private_Manialib_Http_EmptyResultsSuccess() {
  foreach(RequestId => Request in G_Manialib_Http_RequestsResultsSuccess) {
    G_Manialib_Http_RequestsResultsSuccess.removekey(RequestId);
  }
}

Void Private_Manialib_Http_EmptyResultsError() {
  foreach(RequestId => Request in G_Manialib_Http_RequestsResultsError) {
    G_Manialib_Http_RequestsResultsError.removekey(RequestId);
  }
}

Text[Ident] Manialib_Http_PendingResponses() {
  declare Text[Ident] result;
  Private_Manialib_Http_HandleFinishedRequests();
  result = G_Manialib_Http_RequestsResultsSuccess;
  Private_Manialib_Http_EmptyResultsSuccess();
  return result;
}

Text[Ident] Manialib_Http_PendingErrors() {
  declare Text[Ident] result;
  Private_Manialib_Http_HandleFinishedRequests();
  result = G_Manialib_Http_RequestsResultsError;
  Private_Manialib_Http_EmptyResultsError();
  return result;
}

Void Manialib_Http_Loop() {
    Private_Manialib_Http_HandleFinishedRequests();
}
```


