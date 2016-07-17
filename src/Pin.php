<?php

namespace Carica\Gpio {

  use Carica\Io\Device;
  use Carica\Io\Event\Emitter;
  use Carica\Io\Event\HasLoop;
  use Carica\Io\Event\Loop;

  class Pin implements Device\Pin, HasLoop {

    use Emitter\Aggregation;
    use Loop\Aggregation;

    const DIRECTION_OUT = 'out';
    const DIRECTION_IN = 'in';
    const UPDATE_CHANGE_DELTA = 0.0001;


    public static $MODE_LABELS = [
      self::MODE_UNKNOWN => 'unknown',
      self::MODE_INPUT => 'input',
      self::MODE_OUTPUT => 'output',
      self::MODE_ANALOG => 'analog',
      self::MODE_PWM => 'pwm',
      self::MODE_SHIFT => 'shift',
      self::MODE_I2C => 'i2c',
    ];

    private $_pinNumber = 0;
    private $_pinGpioNumber = 0;
    private $_modes = [];
    private $_mode = 0;
    private $_value = 0;

    private $_isInitialized = NULL;
    private $_commands;
    private $_updateInterval;
    private $_updateListener = 0;

    public function __construct(Commands $commands, $pinNumber, array $capabilities = []) {
      $this->_commands = $commands;
      $this->_pinNumber = $this->_pinGpioNumber = (int)$pinNumber;
      foreach ($capabilities as $capability => $value) {
        switch ($capability) {
          case Board::GPIO_PIN_MAPPING :
            $this->_pinGpioNumber = (int)$value;
            break;
          default :
            if (is_int($capability)) {
              $this->addMode($capability, $value);
              break;
            }
            throw new \UnexpectedValueException(
              'Capability "%s" is not a valid option or pin mode.', $capability
            );
        }
      }
    }

    private function initialize() {
      if (!$this->_isInitialized) {
        $this->export();
      }
    }

    private function getGpioNumber() {
      return $this->_pinGpioNumber;
    }

    public function export($direction = self::DIRECTION_OUT) {
      $this->_isInitialized = $this->_commands->export($this->getGpioNumber(), $direction);
    }

    public function unexport() {
      $this->_isInitialized = (0 !== $this->_commands->unexport($this->getGpioNumber()));
    }

    private function addMode($mode, $maximum) {
      switch ($mode) {
        case self::MODE_INPUT :
        case self::MODE_OUTPUT :
          $this->_modes[$mode] = 1;
          return;
        case self::MODE_PWM :
        case self::MODE_ANALOG :
          $this->_modes[$mode] = $maximum;
          return;
      }
      throw new \InvalidArgumentException(
        sprintf(
          'Invalid pin mode "%d" for pin #%d (GPIO%d).', $mode, $this->_pinNumber, $this->_pinGpioNumber
        )
      );
    }

    private function setValue($value) {
      $this->initialize();
      if ($this->_mode == self::MODE_PWM) {
        $this->_commands->pwm($this->getGpioNumber(), round($value * $this->getMaximum()));
      } else {
        $this->_commands->write($this->getGpioNumber(), $value > 0 ? '1' : '0');
      }
      if (abs($value - $this->_value) > self::UPDATE_CHANGE_DELTA) {
        $this->_value = $value;
        $this->emitEvent('change', $this);
      }
    }

    public function supports($mode) {
      return array_key_exists($mode, $this->_modes);
    }

    public function setMode($mode) {
      $this->initialize();
      if ($this->supports($mode)) {
        $this->_mode = $mode;
        $this->_commands->mode($this->getGpioNumber(), $mode);
        $this->prepareOnUpdate();
      } else {
        throw new \InvalidArgumentException(
          sprintf(
            'Unsupported pin mode for pin #%d (GPIO%d)', $this->_pinNumber, $this->_pinGpioNumber
          )
        );
      }
    }

    public function getMode() {
      return $this->_mode;
    }

    public function setDigital($isHigh) {
      $this->setValue($isHigh ? 1 : 0);
    }

    public function getDigital() {
      if (!$this->_updateListener) {
        $this->readValueFromBoard();
      }
      return $this->_value > 0;
    }

    public function setAnalog($percent) {
      if ($percent > 1) {
        $percent = 1.0;
      } elseif ($percent < 0) {
        $percent = 0;
      }
      $this->setValue($percent);
      return $this->_value;
    }

    public function getAnalog() {
      if (!$this->_updateListener) {
        $this->readValueFromBoard();
      }
      return $this->_value;
    }

    public function onChange(callable $callback) {
      $this->events()->on('change', $callback);
    }

    public function getMaximum() {
      return $this->_modes[$this->_mode];
    }

    public function setUpdateInterval($milliseconds) {
      $milliseconds = (int)$milliseconds;
      if ($this->_updateInterval != $milliseconds) {
        $this->_updateInterval = $milliseconds;
        $this->prepareOnUpdate();
      }
    }

    private function prepareOnUpdate() {
      if ($this->_updateListener) {
        $this->loop()->remove($this->_updateListener);
        $this->_updateListener = false;
      }
      if ($this->_mode == Pin::MODE_INPUT || $this->_mode == Pin::MODE_ANALOG) {
        $this->_updateListener = $this->loop()->setInterval(
          function() {
            $oldValue = $this->_value;
            $this->readValueFromBoard();
            if (abs($oldValue - $this->_value) > self::UPDATE_CHANGE_DELTA) {
              $this->emitEvent('change', $this);
            }
          },
          $this->_updateInterval
        );
      }
    }

    private function readValueFromBoard() {
      if ($this->_mode == Pin::MODE_INPUT || $this->_mode == Pin::MODE_ANALOG) {
        $value = (int)$this->_commands->read($this->getGpioNumber());
        if ($this->_mode == self::MODE_ANALOG) {
          $this->_value = $value / $this->getMaximum();
        } else {
          $this->_value = $value > 0 ? 1 : 0;
        }
      }
    }
  }
}