<?php

namespace Carica\Gpio {
  
  class Pins
    implements \ArrayAccess, \Countable, \IteratorAggregate {
    
    private $_pins = [];
    
    public function __construct($capabilities) {
      foreach ($capabilities as $pinNumber => $modes) {
        $this->_pins[$pinNumber] = new Pin($pinNumber, $modes);
      }
    }
    
    public function offsetExists($offset) {
      return array_key_exists($offset, $this->_pins);
    }
    
    public function offsetGet($offset) {
      return $this->_pins[$offset];
    }
    
    public function offsetSet($offset, $value) {
      return \LogicException('Pins can not be set.');
    }
    
    public function offsetUnset($offset) {
      return \LogicException('Pins can not be unset.');
    }
    
    public function count() {
      return count($this->_pins);
    }
    
    public function getIterator() {
      return new \ArrayIterator($this->_pins);
    }
  }
}