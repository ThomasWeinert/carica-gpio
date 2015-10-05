<?php

require(__DIR__.'/../vendor/autoload.php');

$board = new \Carica\Gpio\Boards\RaspberryPiB(new \Carica\Gpio\Commands\GpioTools());
$pin = $board->pins[1];
$pin->setMode(\Carica\Io\Device\Pin::MODE_OUTPUT);

while (TRUE) {
  $pin->setDigital(!$pin->getDigital());
  sleep(1000);
}