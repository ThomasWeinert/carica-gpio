<?php

namespace Carica\Gpio\Boards {

  use Carica\Gpio\Board;
  use Carica\Gpio\Commands;
  use Carica\Io\Device\Pin;

  class RaspberryPiB extends Board {

    public function __construct(Commands $commands) {
      parent::__construct(
        $commands,
        new \ArrayIterator(
          [
            4 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            5 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            6 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            12 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1,
              Pin::MODE_PWM => 255
            ],
            13 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1,
              Pin::MODE_PWM => 255
            ],
            16 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            17 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            18 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            19 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1,
              Pin::MODE_PWM => 255
            ],
            20 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            21 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            22 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            23 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            24 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            25 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            26 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ],
            27 => [
              Pin::MODE_OUTPUT => 1,
              Pin::MODE_INPUT => 1
            ]
          ]
        )
      );
    }
  }
}