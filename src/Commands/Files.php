<?php
namespace Carica\Gpio\Commands {

  use Carica\Gpio\Commands;
  use Carica\Io\Device\Pin;

  class Files implements Commands {

    const PATH_GPIO = '/gpio';
    const PATH_EXPORT = '/export';
    const PATH_UNEXPORT = '/unexport';
    private $_basePath = '/sys/class/gpio';

    /**
     * in/out/pwm/clock/up/down/tri
     * @var array
     */
    private static $_gpioModes = [
      Pin::MODE_INPUT => 0,
      Pin::MODE_OUTPUT => 1,
      Pin::MODE_PWM => 2
    ];

    public function __construct($basePath = NULL) {
      if (!empty($basePath)) {
        $this->_basePath = $basePath;
      }
    }

    private function getFullPath($relativePath, $pinNumber = NULL) {
      $path = $this->_basePath.$relativePath;
      if (is_int($pinNumber)) {
        $path .= '/'.$pinNumber;
      }
      return $path;
    }

    function export($pinNumber, $direction) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber);
      if (!file_exists($file)) {
        file_put_contents($this->getFullPath(self::PATH_EXPORT), $pinNumber);
      }
      file_put_contents($file.'/direction', $direction);
      return file_exists($file);
    }

    function unexport($pinNumber) {
      $file = $this->getFullPath(self::PATH_UNEXPORT);
      if (file_exists($file)) {
        file_put_contents($this->getFullPath(self::PATH_UNEXPORT), $pinNumber);
      }
      return !file_exists($file);
    }

    function direction($pinNumber, $direction) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/direction';
      return file_put_contents($file, $direction);
    }

    function read($pinNumber, $value) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/value';
      return file_get_contents($file, $value > 0 ? 1 : 0);
    }

    function mode($pinNumber, $mode) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/mode';
      file_put_contents($file, self::$_gpioModes[$mode]);
    }

    function write($pinNumber, $value) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/value';
      file_put_contents($file, $value > 0 ? 1 : 0);
    }

    function pwm($pinNumber, $value) {
      $file = $this->getFullPath(self::PATH_GPIO, $pinNumber).'/value';
      file_put_contents($file, $value);
    }
  }
}