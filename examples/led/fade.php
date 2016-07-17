<?php
$board = include('../bootstrap.php');

use Carica\Io;

$loop = Io\Event\Loop\Factory::get();

/** @var \Carica\Gpio\Pin $pin */
$pin = $board->pins[32];
$pin->setMode(Io\Device\Pin::MODE_PWM);
$loop->setInterval(
  function () use ($board, $pin) {
    static $brightness = 0, $step = 5;
    echo 'LED: '.$brightness."\n";
    $pin->setAnalog($brightness / 255);
    $brightness += $step;
    if ($brightness <= 0 || $brightness >= 255) {
      $step = -$step;
    }
  },
  100
);

$loop->run();

