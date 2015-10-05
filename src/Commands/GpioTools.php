<?php
namespace Carica\Gpio\Commands {

  use Carica\Gpio\Commands;
  use Carica\Io\Device\Pin;

  class GpioTools implements Commands {

    /**
     * in/out/pwm/clock/up/down/tri
     * @var array
     */
    private static $_gpioModes = [
      Pin::MODE_INPUT => 'in',
      Pin::MODE_OUTPUT => 'out',
      Pin::MODE_PWM => 'pwm'
    ];

    private $_command = 'gpio';

    function export($pinNumber, $direction) {
      return $this->execute('export', $pinNumber, $direction);
    }

    function unexport($pinNumber) {
      return $this->execute('unexport', $pinNumber, 'in');
    }

    function read($pinNumber, $value) {
      return $this->execute('read', $pinNumber);
    }

    function mode($pinNumber, $mode) {
      return $this->execute('mode', self::$_gpioModes[$mode]);
    }

    function write($pinNumber, $value) {
      return $this->execute('write', $value);
    }

    function pwm($pinNumber, $value) {
      return $this->execute('pwm', $value);
    }

    function execute() {
      $command = escapeshellcmd($this->_command);
      foreach (func_get_args() as $argument) {
        $command .= ' '.escapeshellarg($argument);
      }
      return exec($command);
    }
  }
}