<?php
namespace Carica\Gpio\WiringPi {

  use Carica\Gpio\ShiftOut;
  use Carica\Gpio\Pin;

  class Commands implements \Carica\Gpio\Commands {

    protected $_mappingMode = SETUP_GPIO;
    private static $_initialized = FALSE;

    public function __construct() {
      if (!extension_loaded('wires')) {
        throw new \LogicException('Extension wires not available.');
      }
    }

    private function initialize() {
      if (!self::$_initialized) {
        self::$_initialized = TRUE;
        setup($this->_mappingMode);
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
        return digitalRead($pinNumber);
      case Pin::MODE_PWM :
        return analogRead($pinNumber);
      }
      return 0;
    }

    public function mode($pinNumber, $mode) {
      $this->initialize();
      $modeMap = [
        Pin::MODE_INPUT => INPUT,
        Pin::MODE_OUTPUT => OUTPUT,
        Pin::MODE_PWM => PWM_OUTPUT,
      ];
      switch ($mode) {
      case Pin::MODE_INPUT :
      case Pin::MODE_OUTPUT :
      case Pin::MODE_PWM :
        pinMode($pinNumber, $modeMap[$mode]);
        return TRUE;
      default :
        throw new \InvalidArgumentException(
          sprintf(
            'Pin mode "%s" not support by commands implementation.',
            Pin::$MODE_LABELS[$mode]
          )
        );
      }
    }

    public function write($pinNumber, $mode, $value) {
      $this->initialize();
      switch ($mode) {
      case Pin::MODE_OUTPUT :
        digitalWrite($pinNumber, $value);
        break;
      case Pin::MODE_PWM :
        pwmWrite($pinNumber, $value);
        break;
      }
    }

    public function createShiftOut(Pin $latchPin, Pin $clockPin, Pin $dataPin, $isHighLatch) {
      return new ShiftOut(
        $latchPin, $clockPin, $dataPin, $isHighLatch
      );
    }
  }
}