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
            1 => [
              Pin::MODE_OUTPUT => 1
            ]
          ]
        )
      );
    }
  }
}