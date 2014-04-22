<?php

namespace Anticom\ExceptionAggregator;

use Exception;

class ExceptionContainer extends Exception {
    /**
     * @var Exception[]
     */
    protected $exceptions;

    public function __construct($exceptions = []) {
        $this->exceptions = $exceptions;
    }

    public function addException(Exception $exception) {
        $this->exceptions[] = $exception;
    }

    public function removeException(Exception $exception) {
        if(in_array($exception, $this->exceptions)) {
            unset($this->exceptions[array_search($exception, $this->exceptions)]);
            return true;
        }
        return false;
    }

    public function clearExceptions() {
        $this-> exceptions = [];
    }

    public function getExceptions() {
        return $this->exceptions;
    }

    public function setException($exceptions) {
        $this->exceptions = $exceptions;
    }

    public function countExceptions() {
        return count($this->exceptions);
    }

    public function hasExceptions() {
        return $this->countExceptions() > 0;
    }

    public function throwEventually() {
        if($this->hasExceptions()) {
            throw $this;
        }
    }
}