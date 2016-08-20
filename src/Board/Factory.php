<?php

namespace Carica\Gpio\Board {

  use Carica\Gpio\Board;
  use Carica\Gpio\Commands;

  class Factory {

    /**
     * @param Configuration $configuration
     * @return Board
     */
    public function createBoard(Configuration $configuration) {
      $commandsOption = $configuration->commands;
      if (is_callable($commandsOption)) {
        $commands = $commandsOption($configuration);
      } else {
        $commands = $this->createCommandsByClass($commandsOption);
      }
      $boardOption = $configuration->board;
      if (is_callable($boardOption)) {
        $board = $boardOption($commands);
      } else {
        $board = $this->createBoardByClass($boardOption, $commands);
      }
      return $board;
    }

    private function createBoardByClass($class, Commands $commands) {
      return new $class($commands);
    }

    private function createCommandsByClass($class) {
      return new $class();
    }
  }
}