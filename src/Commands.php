<?php
namespace Carica\Gpio {

  interface Commands {

    function export($pinNumber, $direction);

    function unexport($pinNumber);

    function read($pinNumber);

    function mode($pinNumber, $mode);

    function write($pinNumber, $value);

    function pwm($pinNumber, $value);
  }
}