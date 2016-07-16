<?php

require(__DIR__.'/../vendor/autoload.php');

$loop = \Carica\Io\Event\Loop\Factory::get();

$board = new \Carica\Gpio\Boards\RaspberryPiB(
  //new \Carica\Gpio\Commands\Sysfs()
  new \Carica\Gpio\Commands\GpioTools()
);
/** @var Carica\Io\Device\Pin $pin */
$pin = $board->pins[27];
$pin->setMode(\Carica\Io\Device\Pin::MODE_OUTPUT);

$loop->setInterval(
  function() use ($pin) {
    $pin->setDigital(!$pin->getDigital());
    echo ($pin->getDigital() ? 'on' : 'off'), "\n";
  },
  500
);

$loop->run();

