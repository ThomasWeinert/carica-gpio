<?php
$board = include(__DIR__.'/../bootstrap.php');
$board->pins->exportAll();

/** @var Carica\Io\Device\Pin $pin */
$pin = $board->pins[35];
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

