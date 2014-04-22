<?php

namespace Anticom\ExceptionAggregator;

use Anticom\ExceptionAggregator\Voter\AggregationVoter;
use Exception;

/**
 * Class ExceptionAggregator
 * Aggregates Exceptions
 *
 * Whether a thrown Exception should be aggregated depends on the Voters
 */
class ExceptionAggregator {
    protected $active = false;
    protected $aggregator;
    protected $container;

    public function __construct(AggregationVoter $aggregator, ExceptionContainer $container) {
        $this->aggregator = $aggregator;
        $this->container = $container;
    }

    #region getters & setters
    /**
     * @param AggregationVoter $aggregator
     */
    public function setAggregator($aggregator)
    {
        $this->aggregator = $aggregator;
    }

    /**
     * @return AggregationVoter
     */
    public function getAggregator()
    {
        return $this->aggregator;
    }

    /**
     * @param ExceptionContainer $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return ExceptionContainer
     */
    public function getContainer()
    {
        return $this->container;
    }
    #endregion

    public function handle(Exception $exception, $chain = false) {
        if($this->aggregator->vote($exception)) {
            $this->pass($exception);
            return true;
        } else {
            $this->fail($exception);
            return false;
        }
    }

    protected function pass(Exception $exception) {
        $this->container->addException($exception);
    }

    protected function fail(Exception $exception) {
        //maybe some onFail callback?
    }
}