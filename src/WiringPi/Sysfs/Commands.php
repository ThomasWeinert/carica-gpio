<?php
namespace Carica\Gpio\WiringPi\Sysfs {

  use Carica\Gpio\WiringPi as Wires;

  class Commands extends Gpio {

    private $_gpioTools;
    protected $_mappingMode = Wires\SETUP_SYSFS;

    public function __construct() {
      $this->_gpioTools = new \Carica\Gpio\Commands\GpioTools();
    }

    public function export($pinNumber, $direction) {
      $result = $this->_gpioTools->export($pinNumber, $direction);
      clearstatcache();
      return $result;
    }

    public function unexport($pinNumber) {
      $result = $this->_gpioTools->unexport($pinNumber);
      clearstatcache();
      return $result;
    }
  }
}