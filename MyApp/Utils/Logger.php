<?php

namespace MyApp\Utils;

class Logger {
    public function log ($msg) {
        echo "[LOG]: $msg\n";
    }
}

/*



require_once 'MyApp/Utils/Logger.php';

use MyApp\Utils\Logger;

$logger = new Logger();
$logger->log("Hello from PHP");


*/

