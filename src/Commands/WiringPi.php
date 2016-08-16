<?php
namespace Carica\Gpio\Commands {

  use Carica\Gpio\Commands;
  use Carica\Io\Device\Pin;
  use Carica\Gpio\WiringPi as Wires;

  class WiringPi implements Commands {

    public function __construct($mode = Wires\SETUP_GPIO) {
      if (!extension_loaded('wires')) {
        throw new \LogicException('Extension wires not available.');
      }
      $this->setMappingMode($mode);
    }

    public function setMappingMode($mode) {
      Wires\setup($mode);
    }

    public function export($pinNumber, $direction) {
      return TRUE;
    }

    public function unexport($pinNumber) {
      return TRUE;
    }

    public function read($pinNumber, $mode) {
      switch ($mode) {
      case Pin::MODE_INPUT :
      case Pin::MODE_OUTPUT :
        return Wires\digitalRead($pinNumber);
      case Pin::MODE_PWM :
        return Wires\analogRead($pinNumber);
      }
      return 0;
    }

    public function mode($pinNumber, $mode) {
      $modeMap = [
        Pin::MODE_INPUT => Wires\INPUT,
        Pin::MODE_OUTPUT => Wires\OUTPUT,
        Pin::MODE_PWM => Wires\PWM_OUTPUT,
      ];
      switch ($mode) {
      case Pin::MODE_INPUT :
      case Pin::MODE_OUTPUT :
      case Pin::MODE_PWM :
        return Wires\pinMode($pinNumber, $modeMap[$mode]);
        break;
      default :
        throw new \InvalidArgumentException(
          sprintf(
            'Pin mode "%s" not support by commands implementation.',
            \Carica\Gpio\Pin::$MODE_LABELS[$mode]
          )
        );
      }
    }

    public function write($pinNumber, $mode, $value) {
      switch ($mode) {
      case Pin::MODE_OUTPUT :
        Wires\digitalWrite($pinNumber, $value);
        break;
      case Pin::MODE_PWM :
        Wires\pwmWrite($pinNumber, $value);
        break;
      }
    }
  }
}