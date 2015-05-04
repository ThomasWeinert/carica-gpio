<?php

namespace Carica\Gpio {

  use \Carica\Io\Device;
  use \Carica\Io\Event\Emitter;
  
  class Pin implements Device\Pin {
    
    use Emitter\Aggregation;
    
    const PATH_GPIO = '/sys/class/gpio/gpio';
    const PATH_EXPORT = '/sys/class/gpio/export';
    const PATH_UNEXPORT = '/sys/class/gpio/unexport';
    
    private $_number = 0;
    private $_modes = [];
    private $_mode = 0;
    private $_value = 0; 

    private $_path = NULL;
    
    
    public function __construct($pinNumber, $supports) {
      $this->_pinNumber = (int)$pinNumber;
      foreach ($supports as $mode => $maximum) {
        $this->addMode($mode, $maximum);
      }
    }
    
    private function initialize() {
      if (!$this->_path) {
        $this->_path = self::PATH_GPIO.'/'.$this->_number;
        $this->export();
      }
    }
    
    public function export() {
      if (!$this->_path) {
        $this->initialize();
      } elseif (!file_exists($this->_path)) {
        file_put_contents(self::PATH_EXPORT, $this->_pinNumber);
      }
    } 
    
    public function unexport() {
      file_put_contents(self::PATH_UNEXPORT, $this->_pinNumber);
      $this->_path = FALSE;
    }
    
    private function addMode($mode, $maximum) {
      switch ($mode) {
      case self::MODE_INPUT : 
      case self::MODE_OUTPUT : 
        $this->_modes[$mode] = 1;
        return;
      }
      throw new \InvalidArgumentException('Invalid pin mode for pin #', $this->_pinNumber);
    }
    
    private function setValue($value) {
      $this->initialize();
      file_put_contents($this->_path.'/value', round($value * $this->getMaximum()));
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
        file_put_contents($this->_path.'/mode', $this->mapPinModeToGpio($mode));
        $this->_mode = $mode;
      } else {
        throw new \InvalidArgumentException('Unsupported pin mode for pin #', $this->_pinNumber);
      }
    }
    
    public function getMode() {
      return $this->_mode;
    }
    
    public function setDigital($isHigh) {
      $this->setValue($isHigh ? 0.0 : 1.0);
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