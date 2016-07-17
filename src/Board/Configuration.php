<?php

namespace Carica\Gpio\Board {

  use Carica\Gpio\Boards\RaspberryPi;
  use Carica\Gpio\Commands;

  /**
   * Class Configuration
   *
   * @property string|callable $board
   * @property string|callable $commands
   */
  class Configuration {

    const DEFAULTS = [
      'board' => RaspberryPi\Rev2::class,
      'commands' => Commands::class
    ];

    private $_options = self::DEFAULTS;

    public function __construct(array $options = []) {
      foreach ($options as $name => $value) {
        $this->$name = $value;
      }
    }

    public function __isset($name) {
      return isset($this->_options[$name]);
    }

    public function __get($name) {
      if (array_key_exists($name, $this->_options)) {
        return $this->_options[$name];
      }
    }

    public function __set($name, $value) {
      if (array_key_exists($name, self::DEFAULTS)) {
        $setter = 'set'.ucfirst($name);
        if (method_exists($this, $setter)) {
          $this->$setter($value);
        } else {
          $this->_options[$name] = self::DEFAULTS[$name];
        }
      } else {
        throw new \InvalidArgumentException(
          sprintf(
            'Unknown option "%s".', $name
          )
        );
      }
    }

    public function __unset($name) {
      if (array_key_exists($name, self::DEFAULTS)) {
        $this->_options[$name] = self::DEFAULTS[$name];
      }
    }

    public function setBoard($option) {
      if (is_string($option) || is_callable($option)) {
        $this->_options['board'] = $option;
      } else {
        throw new \InvalidArgumentException(
          'Board option needs to be a class name or a callable.'
        );
      }
    }

    public function setCommands($option) {
      if (is_string($option) || is_callable($option)) {
        $this->_options['commands'] = $option;
      } else {
        throw new \InvalidArgumentException(
          'Commands option needs to be a class name or a callable.'
        );
      }
    }
  }
}