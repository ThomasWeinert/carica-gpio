<?php
namespace Carica\Gpio\Commands\WiringPi {

  use Carica\Io\Device\Pin;
  use Carica\Gpio\WiringPi as Wires;

  class Gpio implements \Carica\Gpio\Commands {

    protected $_mappingMode = Wires\SETUP_GPIO;
    private static $_initialized = FALSE;

    public function __construct() {
      if (!extension_loaded('wires')) {
        throw new \LogicException('Extension wires not available.');
      }
    }

    private function initialize() {
      if (!self::$_initialized) {
        self::$_initialized = TRUE;
        Wires\setup($this->_mappingMode);
      }
    }

    public function export($pinNumber, $direction) {
      return TRUE;
    }

    public function unexport($pinNumber) {
      return TRUE;
    }

    public function read($pinNumber, $mode) {
      $this->initialize();
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
      $this->initialize();
      $modeMap = [
        Pin::MODE_INPUT => Wires\INPUT,
        Pin::MODE_OUTPUT => Wires\OUTPUT,
        Pin::MODE_PWM => Wires\PWM_OUTPUT,
      ];
      switch ($mode) {
      case Pin::MODE_INPUT :
      case Pin::MODE_OUTPUT :
      case Pin::MODE_PWM :
        Wires\pinMode($pinNumber, $modeMap[$mode]);
        return TRUE;
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
      $this->initialize();
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