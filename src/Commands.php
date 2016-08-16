<?php
namespace Carica\Gpio {

  interface Commands {

    function export($pinNumber, $direction);

    function unexport($pinNumber);

    function read($pinNumber, $mode);

    function mode($pinNumber, $mode);

    function write($pinNumber, $mode, $value);
  }
}