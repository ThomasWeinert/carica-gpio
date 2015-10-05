<?php
namespace Carica\Gpio {

  interface Commands {

    function export($pinNumber, $direction);

    function unexport($pinNumber);

    function direction($pinNumber, $direction);

    function read($pinNumber, $value);

    function mode($pinNumber, $mode);

    function write($pinNumber, $value);

    function pwm($pinNumber, $value);
  }
}