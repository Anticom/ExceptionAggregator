<?php

namespace Anticom\ExceptionAggregator;
use Exception;

/**
 * Class AggregationManager
 * Manages ExceptionAggregators
 */
class AggregationManager {
    /** @var  ExceptionAggregator[] */
    protected $aggregators;
    protected $enabledAggregators = array();

    protected $fetchingActive = false;
    protected $shutdownHandlerActive = true;

    public function __construct($aggregators = []) {
        $this->setAggregators($aggregators);
    }

    #region aggregator management
    public function addAggregator(ExceptionAggregator $aggregator, $enable = true) {
        $hash = spl_object_hash($aggregator);

        $this->aggregators[$hash] = $aggregator;
        if($enable) {
            $this->enableAggregator($aggregator);
        }
    }

    public function removeAggregator(ExceptionAggregator $aggregator) {
        if(in_array($aggregator, $this->aggregators)) {
            $hash = spl_object_hash($aggregator);
            unset($this->aggregators[$hash]);

            $this->disableAggregator($aggregator);

            return true;
        }
        return false;
    }

    public function clearAggregators() {
        $this->aggregators = [];
    }

    public function getAggregators() {
        return $this->aggregators;
    }

    public function setAggregators($aggregators) {
        $this->clearAggregators();
        foreach($aggregators as $a) {
            $this->addAggregator($a);
        }
    }

    public function enableAggregator(ExceptionAggregator $aggregator) {
        $hash = spl_object_hash($aggregator);
        if(!in_array($hash, $this->enabledAggregators)) {
            $this->enabledAggregators[] = $hash;
        }
    }

    public function disableAggregator(ExceptionAggregator $aggregator) {
        $hash = spl_object_hash($aggregator);
        if(in_array($hash, $this->enabledAggregators)) {
            unset($this->enabledAggregators[$hash]);
        }
    }
    #endregion

    #region behaviour
    public function enableFetching() {
        if(!$this->fetchingActive) {
            set_exception_handler([$this, 'handleException']);

            $this->fetchingActive = true;
        }
    }

    public function disableFetching() {
        if($this->fetchingActive) {
            restore_exception_handler();

            $this->fetchingActive = false;
        }
    }

    public function handleException(Exception $exception) {
        $aggregators = $this->getEnabledAggregators();
        $handled = false;

        foreach($aggregators as $a) {
            if($a->handle($exception)) {
                $handled = true;
            }
        }

        if(!$handled) {
            $this->disableFetching();
            throw $exception;
        }
    }
    #endregion

    #region auxiliaries
    /**
     * @return ExceptionAggregator[]
     */
    protected function getEnabledAggregators() {
        $enabled = [];

        foreach($this->aggregators as $a) {
            $hash = spl_object_hash($a);
            if(in_array($hash, $this->enabledAggregators)) {
                $enabled[] = $a;
            }
        }

        return $enabled;
    }
    #endregion
}