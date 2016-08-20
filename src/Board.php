<?php

namespace Carica\Gpio {

  /**
   * @property Commands $commands
   * @property Pins $pins
   */
  class Board {

    const GPIO_PIN_MAPPING = 'gpio_pin';

    /**
     * @var Commands
     */
    private $_commands = NULL;
    /**
     * @var \Traversable
     */
    private $_capabilities = NULL;

    /**
     * @var int update interval for input pins
     */
    private $_updateInterval = 100;

    /**
     * @var Pins
     */
    private $_pins;

    public function __construct(Commands $commands, $capabilities) {
      if (!(is_array($capabilities) || $capabilities instanceof \Traversable)) {
        throw new \InvalidArgumentException('Capabilities needs to be an array or Traversable.');
      };
      $this->_commands = $commands;
      $this->_capabilities = $capabilities;
    }

    public function __get($name) {
      switch ($name) {
      case 'pins' :
        return $this->pins();
      case 'commands' :
        return $this->commands();
      }
      throw new \LogicException(sprintf('Unknown property %s::$%s', __CLASS__, $name));
    }

    public function pins(Pins $pins = NULL) {
      if ($pins) {
        $this->_pins = $pins;
      } else if (!$this->_pins) {
        $this->_pins = new Pins($this->_commands, $this->_capabilities);
      }
      return $this->_pins;
    }

    public function commands() {
      return $this->_commands;
    }

    /**
     * Set the update interval for input reading, 0 disables the
     * automatic update, it will update if you read the pin value.
     *
     * @param int $milliseconds
     */
    public function setUpdateInterval($milliseconds) {
      $this->_updateInterval = $milliseconds;
      if (isset($this->_pins)) {
        $this->_pins->setUpdateInterval($milliseconds);
      }
    }
  }
}