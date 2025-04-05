<?php

namespace OOP\Core;


// spl_autoload_register(...) = “Hey! Every time a class is used but hasn’t been loaded yet — call this function to try to load it.”

spl_autoload_register(function ($class) {
    // This is a cross-platform way to convert a namespace into a file path: e.g $class = "App\\Utils\\Logger";
    // $file = "App/Utils/Logger.php"; // or App\Utils\Logger.php on Windows

    //  DIRECTORY_SEPARATOR used So this works correctly on Linux, Mac, and Windows Instead of hardcoding / or \

    // N.B: DIRECTORY_SEPARATOR knows which os the php code is running and based on that it uses \ (windows) or / (linux)


    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});


