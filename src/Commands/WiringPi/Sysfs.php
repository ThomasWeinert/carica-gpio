<?php
namespace Carica\Gpio\Commands\WiringPi {

  use Carica\Gpio\Commands;
  use Carica\Gpio\WiringPi as Wires;

  class Sysfs extends Commands\WiringPi {

    private $_sysfsCommands;

    public function __construct() {
      parent::__construct(Wires\SETUP_SYSFS);
      $this->_sysfsCommands = new Commands\Sysfs();
    }

    public function export($pinNumber, $direction) {
      return $this->_sysfsCommands->export($pinNumber, $direction);
    }

    public function unexport($pinNumber) {
      return $this->_sysfsCommands->unexport($pinNumber);
    }
  }
}