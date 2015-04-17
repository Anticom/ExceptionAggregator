<?php

namespace Anticom\ExceptionAggregator;

use Anticom\ExceptionAggregator\Voter\AggregationVoterInterface;
use Exception;

/**
 * Class ExceptionAggregator
 * Aggregates Exceptions
 *
 * Whether a thrown Exception should be aggregated depends on the Voters
 */
class ExceptionAggregator
{
    protected $active = false;
    protected $aggregatorInterface;
    protected $container;

    public function __construct(AggregationVoterInterface $aggregatorInterface = null, ExceptionContainer $container = null)
    {
        $this->aggregatorInterface = $aggregatorInterface;
        if(null === $container) {
            $this->container = new ExceptionContainer();
        } else {
            $this->container = $container;
        }
    }

    #region getters & setters
    /**
     * @param AggregationVoterInterface $aggregatorInterface
     */
    public function setAggregatorInterface($aggregatorInterface)
    {
        $this->aggregatorInterface = $aggregatorInterface;
    }

    /**
     * @return AggregationVoterInterface
     */
    public function getAggregatorInterface()
    {
        return $this->aggregatorInterface;
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

    public function handle(Exception $exception, $chain = false)
    {
        //TODO throw proper Exception here, not just the plain one
        if(null === $this->aggregatorInterface) {
            throw new Exception("No Aggregation Voter assigned to Exception Aggregator!");
        }

        if ($this->aggregatorInterface->vote($exception)) {
            $this->pass($exception);
            return true;
        } else {
            $this->fail($exception);
            return false;
        }
    }

    protected function pass(Exception $exception)
    {
        $this->container->addException($exception);
    }

    protected function fail(Exception $exception)
    {
        //maybe some onFail callback?
    }
}