<?php

namespace Anticom\ExceptionAggregator\Voter;

use Exception;

interface AggregationVoter {
    /**
     * Checks, whether a concrete Exception should be aggregated or bubble up the call stack
     *
     * @param Exception $exception
     * @return bool     true if should be aggregated, false if should bubble up the call stack
     */
    public function vote(Exception $exception);
}