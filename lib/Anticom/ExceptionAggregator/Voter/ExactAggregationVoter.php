<?php

namespace Anticom\ExceptionAggregator\Voter;

use Exception;

class ExactAggregationVoter implements AggregationVoterInterface
{
    /**
     * @var array
     */
    protected $aggregate;

    /**
     * @param array $aggregate FQNs of Exceptions to aggregate from
     */
    public function __construct($aggregate = [])
    {
        $this->aggregate = $aggregate;
    }

    public function vote(Exception $exception)
    {
        foreach ($this->aggregate as $aggregate) {
            if (get_class($exception) == $aggregate) {
                return true;
            }
        }

        return false;
    }
}