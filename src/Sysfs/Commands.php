<?php
namespace Carica\Gpio\Sysfs {

  use Carica\Io\Device\Pin;

  class Commands implements \Carica\Gpio\Commands {

    const PATH_GPIO = '/sys/class/gpio';
    const PATH_EXPORT = '/sys/class/gpio/export';
    const PATH_UNEXPORT = '/sys/class/gpio/unexport';
    const PIN_PREFIX = 'gpio';

    private function getFullPath($basePath, $pinNumber = NULL) {
      $path = $basePath;
      if (is_int($pinNumber)) {
        $path .= '/'.self::PIN_PREFIX.$pinNumber;
      }
      return $path;
    }

    public function export($pinNumber, $direction) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber);
      if (!file_exists($file)) {
        $this->writeGpio($this->getFullPath(self::PATH_EXPORT), $pinNumber);
        clearstatcache(FALSE, $file);
        clearstatcache(FALSE, $file.'/direction');
      }
      $this->writeGpio($file.'/direction', $direction);
      return file_exists($file);
    }

    public function unexport($pinNumber) {
      $file = $this->getFullPath(self::PATH_UNEXPORT);
      if (file_exists($file)) {
        $this->writeGpio($this->getFullPath(self::PATH_UNEXPORT), $pinNumber);
        clearstatcache(FALSE, $file);
      }
      return !file_exists($file);
    }

    public function read($pinNumber, $mode) {
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

    public function write($pinNumber, $mode, $value) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/value';
      switch ($mode) {
      case Pin::MODE_OUTPUT :
        $this->writeGpio($file, $value > 0 ? 1 : 0);
        break;
      case Pin::MODE_PWM :
        $this->writeGpio($file, $value);
        break;
      }
    }

    private function readGpio($file) {
      return file_get_contents($file);
    }

    private function writeGpio($file, $value) {
      file_put_contents($file, $value);
    }
  }
}