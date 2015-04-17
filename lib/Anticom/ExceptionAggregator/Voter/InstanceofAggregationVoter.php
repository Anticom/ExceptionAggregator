<?php

namespace Anticom\ExceptionAggregator\Voter;

use Exception;

class InstanceofAggregationVoter implements AggregationVoterInterface
{
    protected $aggregate;

    public function __construct($aggregate = [])
    {
        $this->aggregate = $aggregate;
    }

    public function vote(Exception $exception)
    {
        foreach ($this->aggregate as $aggregate) {
            if ($exception instanceof $aggregate) {
                return true;
            }
        }

        return false;
    }
}