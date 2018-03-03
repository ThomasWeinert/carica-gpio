<?php
namespace Carica\Gpio {

  class I2C implements \Carica\Io\Device\I2C {

    private $_bus;

    public function __construct($bus) {
      $this->_bus = (int)$bus;
    }

    public function write($slaveAddress, $data) {
      $command = sprintf('i2cset -y %d %s', $this->_bus, $this->getByteAsString($slaveAddress));
      foreach ($data as $byte) {
        $command .= ' '.$this->getByteAsString($byte);
      }
      echo $command, "\n";
    }

    public function read($slaveAddress, $length) {
      $command = sprintf('i2cget -y %d %s', $this->_bus, $this->getByteAsString($slaveAddress));
      echo $command, "\n";
    }

    public function startReading($slaveAddress, $length, callable $listener) {
      // TODO: Implement startReading() method.
    }

    public function stopReading($slaveAddress) {
      // TODO: Implement stopReading() method.
    }

    private function execute($command, ...$arguments) {

    }

    private function getByteAsString($byte) {
      return '0x'.str_pad(dechex($byte), 2, '0', STR_PAD_LEFT);
    }
  }
}
