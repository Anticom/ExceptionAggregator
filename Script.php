<?php

use Anticom\ExceptionAggregator\AggregationManager;
use Anticom\ExceptionAggregator\Voter\ExactAggregationVoter;

require_once "vendor/autoload.php";

$av = new ExactAggregationVoter([
    'Exception'
]);
$ea = new \Anticom\ExceptionAggregator\ExceptionAggregator($av);
$am = AggregationManager::getInstance();
$am->addAggregator($ea);

function throwDemExceptions() {
    throw new Exception("this is a test exception");
}

print "before\n";
throwDemExceptions();
print "after\n";

print_r($am);

class Script {
    public static $manager;

    public static function notify(Exception $exception) {
        self::$manager->handleException($exception);
    }
}