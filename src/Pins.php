<?php

namespace Carica\Gpio {
  
  class Pins
    implements \ArrayAccess, \Countable, \IteratorAggregate {
    
    private $_pins = [];
    private $_commands = NULL;
    
    public function __construct(Commands $commands, $capabilities) {
      $this->_commands = $commands;
      foreach ($capabilities as $pinNumber => $pinCapabilities) {
        $this->_pins[$pinNumber] = new Pin($this->_commands, $pinNumber, $pinCapabilities);
      }
    }

    /**
     * @param int $offset
     */
    public function offsetExists($offset) {
      return array_key_exists($offset, $this->_pins);
    }

    /**
     * @param int $offset
     * @return Pin
     */
    public function offsetGet($offset) {
      if (!array_key_exists($offset, $this->_pins)) {
        throw new \InvalidArgumentException(sprintf('Pin "%d" is not defined for this board.', $offset));
      }
      return $this->_pins[$offset];
    }

    /**
     * @param int $offset
     * @param Pin $value
     */
    public function offsetSet($offset, $value) {
      return \LogicException('Pins can not be set.');
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset) {
      return \LogicException('Pins can not be unset.');
    }
    
    public function count() {
      return count($this->_pins);
    }
    
    public function getIterator() {
      return new \ArrayIterator($this->_pins);
    }

    public function setUpdateInterval($milliseconds) {
      foreach ($this->_pins as $pin) {
        $pin->setUpdateInterval($milliseconds);
      }
    }

    public function exportAll() {
      foreach ($this->_pins as $pin) {
        $pin->export();
      }
    }
  }
}