<?php

namespace Carica\Gpio\Boards\RaspberryPi {

  use Carica\Gpio\Board;
  use Carica\Gpio\Commands;
  use Carica\Io\Device\Pin;

  class Rev2 extends Board {

    public function __construct(Commands $commands) {
      parent::__construct(
        $commands,
        new \ArrayIterator(
          [
            3 => [
              self::GPIO_PIN_MAPPING => 2,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1,
              Pin::MODE_I2C => 1
            ],
            5 => [
              self::GPIO_PIN_MAPPING => 3,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1,
              Pin::MODE_I2C => 1
            ],
            7 => [
              self::GPIO_PIN_MAPPING => 4,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            8 => [
              self::GPIO_PIN_MAPPING => 14,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            10 => [
              self::GPIO_PIN_MAPPING => 15,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            11 => [
              self::GPIO_PIN_MAPPING => 17,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            12 => [
              self::GPIO_PIN_MAPPING => 18,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            13 => [
              self::GPIO_PIN_MAPPING => 27,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            15 => [
              self::GPIO_PIN_MAPPING => 22,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            16 => [
              self::GPIO_PIN_MAPPING => 23,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            18 => [
              self::GPIO_PIN_MAPPING => 24,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            19 => [
              self::GPIO_PIN_MAPPING => 10,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            21 => [
              self::GPIO_PIN_MAPPING => 9,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            22 => [
              self::GPIO_PIN_MAPPING => 25,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            23 => [
              self::GPIO_PIN_MAPPING => 11,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            24 => [
              self::GPIO_PIN_MAPPING => 8,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            26 => [
              self::GPIO_PIN_MAPPING => 7,
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ]
          ]
        )
      );
    }
  }
}