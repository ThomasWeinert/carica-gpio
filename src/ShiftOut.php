<?php
namespace Carica\Gpio {

  use Carica\Io\Device;

  class ShiftOut implements Device\ShiftOut {

    /**
     * @var Pin
     */
    private $_latchPin = NULL;
    /**
     * @var Pin
     */
    private $_clockPin = NULL;
    /**
     * @var Pin
     */
    private $_dataPin = NULL;

    private $_highLatch = FALSE;

    public function __construct(Pin $latch, Pin $clock, Pin $data, $highLatch = FALSE) {
      $this->_latchPin = $latch;
      $this->_clockPin = $clock;
      $this->_dataPin = $data;
      $this->_highLatch = (bool)$highLatch;
    }

    public function write($data, $isBigEndian = TRUE) {
      $this->begin();
      $this->transfer($data, $isBigEndian);
      $this->end();
    }

    /**
     * Begin transfer (put the latch pin to low)
     */
    public function begin() {
      $this->_latchPin->setMode(Pin::MODE_OUTPUT);
      $this->_clockPin->setMode(Pin::MODE_OUTPUT);
      $this->_dataPin->setMode(Pin::MODE_OUTPUT);
      $this->_latchPin->setDigital($this->_highLatch);
    }

    /**
     * Begin transfer (put the latch pin to high)
     */
    public function end() {
      $this->_latchPin->setDigital(!$this->_highLatch);
    }

    public function transfer($data, $isBigEndian = TRUE) {
      $write = function($value, $mask) {
        $this->_clockPin->setDigital(FALSE);
        $this->_dataPin->setDigital($value & $mask);
        $this->_clockPin->setDigital(TRUE);
      };

      if (is_string($data)) {
        $values = array_slice(unpack("C*", "\0".$data), 1);
      } elseif (is_array($data)) {
        $values = $data;
      } else {
        $values = array((int)$data);
      }

      foreach ($values as $value) {
        if ($isBigEndian) {
          for ($mask = 128; $mask > 0; $mask = $mask >> 1) {
            $write($value, $mask);
          }
        } else {
          for ($mask = 0; $mask < 128; $mask = $mask << 1) {
            $write($value, $mask);
          }
        }
      }
    }
  }
}
