<?php
namespace Carica\Gpio\WiringPi\Sysfs {

  use Carica\Gpio\WiringPi;

  class Commands extends WiringPi\Commands {

    private $_gpioTools;
    protected $_mappingMode = WiringPi\SETUP_SYSFS;

    public function __construct() {
      $this->_gpioTools = new \Carica\Gpio\GpioTools\Commands();
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