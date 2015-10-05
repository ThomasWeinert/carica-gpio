<?php

namespace Carica\Gpio {

  use \Carica\Io\Device;
  use \Carica\Io\Event\Emitter;
  
  class Pin implements Device\Pin {
    
    use Emitter\Aggregation;

    const DIRECTION_OUT = 'out';
    const DIRECTION_IN = 'in';

    private $_pinNumber = 0;
    private $_modes = [];
    private $_mode = 0;
    private $_value = 0; 

    private $_isInitialized = NULL;

    public function __construct(Commands $commands, $pinNumber, array $supports = []) {
      $this->_commands = $commands;
      $this->_pinNumber = (int)$pinNumber;
      foreach ($supports as $mode => $maximum) {
        $this->addMode($mode, $maximum);
      }
    }
    
    private function initialize() {
      if (!$this->_isInitialized) {
        $this->export();
      }
    }
    
    public function export($direction = self::DIRECTION_OUT) {
      $this->_isInitialized = $this->_commands->export($this->_pinNumber, $direction);
    } 
    
    public function unexport() {
      $this->_isInitialized = !$this->_commands->unexport($this->_pinNumber);
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
      }
      throw new \InvalidArgumentException('Invalid pin mode for pin #', $this->_pinNumber);
    }
    
    private function setValue($value) {
      $this->initialize();
      if ($this->_mode == self::MODE_PWM) {
        $this->_commands->pwm($this->_pinNumber, round($value * $this->getMaximum()));
      } else {
        $this->_commands->write($this->_pinNumber, $value > 0);
      }
      if ($value != $this->_value) {
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
        $this->_commands->mode($this->_pinNumber, $mode);
        $this->_mode = $mode;
      } else {
        throw new \InvalidArgumentException('Unsupported pin mode for pin #', $this->_pinNumber);
      }
    }
    
    public function getMode() {
      return $this->_mode;
    }
    
    public function setDigital($isHigh) {
      $this->setValue($isHigh ? 0 : 1);
    }
    
    public function getDigital() {
      return $this->_value != 0;
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
      return $this->_value;
    }
    
    public function onChange(callable $callback) {
      $this->events()->on('change', $callback);
    }
    
    public function getMaximum() {
      return $this->_modes[$this->_mode];
    }
  }
}