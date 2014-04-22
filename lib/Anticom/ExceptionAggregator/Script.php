<?php

namespace Anticom\ExceptionAggregator;

use Exception;

class Script {
    public static $manager;

    public static function notify(Exception $exception) {
        self::$manager->handleException($exception);
    }
}