<?php

// 🧠 AUTOLoader Robot Setup:
spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

// 🧸 Just using the class — magic happens
$car = new \MyApp\Toys\CarToy();
$car->drive(); // Outputs: Vroom vroom!
