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
      Pin::MODE_OUTPUT => 'out'
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

    public function read($pinNumber) {
      return $this->execute('-g', 'read', $pinNumber);
    }

    public function write($pinNumber, $value) {
      return $this->execute('-g', 'write', $pinNumber, $value);
    }

    public function pwm($pinNumber, $value) {
      return $this->execute('-g', 'pwm', $pinNumber, $value);
    }

    private function execute($options, ...$arguments) {
      $command = escapeshellcmd($this->_command);
      if ($options) {
        $command .= ' '.$options;
      }
      foreach ($arguments as $argument) {
        $command .= ' '.escapeshellarg($argument);
      }
      echo $command, "\n";
      system($command, $result);
      return ($result === 0);
    }
  }
}