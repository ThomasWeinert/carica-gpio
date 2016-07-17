<?php
namespace Carica\Gpio\Commands {

  use Carica\Gpio\Commands;
  use Carica\Io\Device\Pin;

  class Sysfs implements Commands {

    const PATH_GPIO = '/gpio';
    const PATH_EXPORT = '/gpio/export';
    const PATH_UNEXPORT = '/gpio/unexport';
    const PIN_PREFIX = 'gpio';
    private $_basePath = '/sys/class';

    public function __construct($basePath = NULL) {
      if (!empty($basePath)) {
        $this->_basePath = $basePath;
      }
    }

    private function getFullPath($relativePath, $pinNumber = NULL) {
      $path = $this->_basePath.$relativePath;
      if (is_int($pinNumber)) {
        $path .= '/'.self::PIN_PREFIX.$pinNumber;
      }
      return $path;
    }

    public function export($pinNumber, $direction) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber);
      if (!file_exists($file)) {
        $this->writeGpio($this->getFullPath(self::PATH_EXPORT), $pinNumber);
      }
      $this->writeGpio($file.'/direction', $direction);
      return file_exists($file);
    }

    public function unexport($pinNumber) {
      $file = $this->getFullPath(self::PATH_UNEXPORT);
      if (file_exists($file)) {
        $this->writeGpio($this->getFullPath(self::PATH_UNEXPORT), $pinNumber);
      }
      return !file_exists($file);
    }

    public function read($pinNumber) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/value';
      return $this->readGpio($file);
    }

    public function mode($pinNumber, $mode) {
      switch ($mode) {
        case Pin::MODE_INPUT :
          $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/direction';
          $this->writeGpio($file, 'in');
          break;
        case Pin::MODE_OUTPUT :
          $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/direction';
          $this->writeGpio($file, 'out');
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

    public function write($pinNumber, $value) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/value';
      $this->writeGpio($file, $value > 0 ? 1 : 0);
    }

    public function pwm($pinNumber, $value) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/value';
      $this->writeGpio($file, $value);
    }

    private function readGpio($file) {
      return file_get_contents($file);
    }

    private function writeGpio($file, $value) {
      file_put_contents($file, $value);
    }
  }
}