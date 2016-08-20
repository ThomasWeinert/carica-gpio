<?php
namespace Carica\Gpio {

  use Carica\Io\Device\ShiftOut;

  interface Commands {

    /**
     * @param int $pinNumber
     * @param string $direction
     */
    function export($pinNumber, $direction);

    /**
     * @param int $pinNumber
     */
    function unexport($pinNumber);

    /**
     * @param int $pinNumber
     * @param int $mode
     * @return int
     */
    function read($pinNumber, $mode);

    /**
     * @param int $pinNumber
     * @param int $mode
     */
    function mode($pinNumber, $mode);

    /**
     * @param int $pinNumber
     * @param int $mode
     * @param int $value
     */
    function write($pinNumber, $mode, $value);

    /**
     * @param Pin $latchPin
     * @param Pin $clockPin
     * @param Pin $dataPin
     * @param bool $isHighLatch
     * @return ShiftOut
     */
    function createShiftOut(Pin $latchPin, Pin $clockPin, Pin $dataPin, $isHighLatch);
  }
}