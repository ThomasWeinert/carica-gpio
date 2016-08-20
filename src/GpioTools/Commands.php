<?php
namespace Carica\Gpio\GpioTools {

  use Carica\Io\Device\Pin;

  class Commands implements \Carica\Gpio\Commands {

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

    public function export($pinNumber, $direction) {
      return $this->execute('', 'export', $pinNumber, $direction);
    }

    public function direction($pinNumber, $direction) {
      return $this->execute('', 'export', $pinNumber, $direction);
    }

    public function mode($pinNumber, $mode) {
      switch ($mode) {

      }
      return $this->execute('-g', 'mode', $pinNumber, self::$_gpioModes[$mode]);
    }

    public function unexport($pinNumber) {
      return $this->execute('', 'unexport', $pinNumber, 'in');
    }

    public function read($pinNumber, $mode) {
      return $this->execute('-g', 'read', $pinNumber);
    }

    public function write($pinNumber, $mode, $value) {
      switch ($mode) {
      case Pin::MODE_OUTPUT :
        return $this->execute('-g', 'write', $pinNumber, $value);
      case Pin::MODE_PWM :
        return $this->execute('-g', 'pwm', $pinNumber, $value);
      }
    }

    private function execute($options, ...$arguments) {
      $command = escapeshellcmd($this->_command);
      if ($options) {
        $command .= ' '.$options;
      }
      foreach ($arguments as $argument) {
        $command .= ' '.escapeshellarg($argument);
      }
      system($command, $result);
      return ($result === 0);
    }
  }
}