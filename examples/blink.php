<?php
$board = include('bootstrap.php');

/** @var Carica\Io\Device\Pin $pin */
$pin = $board->pins[27];
$pin->setMode(\Carica\Io\Device\Pin::MODE_OUTPUT);

$loop = \Carica\Io\Event\Loop\Factory::get();
$loop->setInterval(
  function() use ($pin) {
    $pin->setDigital(!$pin->getDigital());
    echo ($pin->getDigital() ? 'on' : 'off'), "\n";
  },
  2000
);

$loop->run();

