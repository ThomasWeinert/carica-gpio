<?php

namespace Carica\Gpio\Board {

  use Carica\Gpio\Commands;

  class Factory {

    public function getBoard(Configuration $configuration) {
      $commandsOption = $configuration->commands;
      if (is_callable($commandsOption)) {
        $commands = $commandsOption($configuration);
      } else {
        $commands = $this->getCommandsByClass($commandsOption);
      }
      $boardOption = $configuration->board;
      if (is_callable($boardOption)) {
        $board = $boardOption($commands);
      } else {
        $board = $this->getBoardByClass($boardOption, $commands);
      }
      return $board;
    }

    private function getBoardByClass($class, Commands $commands) {
      return new $class($commands);
    }

    private function getCommandsByClass($class) {
      return new $class();
    }
  }
}