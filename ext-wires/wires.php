<?php
/**
 * This is not an implementation, the functions in this file are provided by
 * the ext/wires extension.
 *
 * This files provides the necessary information for code completion
 */

namespace Carica\Gpio\WiringPi {

  const SETUP_GPIO = 0;
  const SETUP_SYSFS = 1;
  const SETUP_WIRINGPI = 2;

  const INPUT = 0;
  const OUTPUT = 1;
  const PWM_OUTPUT = 2;
  const GPIO_CLOCK = 3;
  const SOFT_PWM_OUTPUT = 4;
  const SOFT_TONE_OUTPUT = 5;
  const PWM_TONE_OUTPUT = 6;

  const LOW = 0;
  const HIGH = 1;

  const LSBFIRST = 0;
  const MSBFIRST = 1;

  /**
   * @param int $mode
   */
  function setup($mode = SETUP_GPIO) {
  }

  /**
   * @param int $pin
   * @param int $mode
   */
  function pinMode($pin, $mode) {
  }

  /**
   * @param int $pin
   * @param int $mode
   */
  function pinModeAlt($pin, $mode) {
  }

  /**
   * @param int $pin
   * @return int
   */
  function digitalRead($pin) {
  }

  /**
   * @param int $pin
   * @param int $value
   */
  function digitalWrite($pin, $value) {
  }

  /**
   * @param int $pin
   * @param int $value
   */
  function pwmWrite($pin, $value) {
  }

  /**
   * @param int $pin
   * @return int
   */
  function analogRead($pin) {
  }

  /**
   * @param int $pin
   * @param int $value
   */
  function analogWrite($pin, $value) {
  }

  /**
   * @param int $dataPin
   * @param int $clockPin
   * @param int $order
   * @param int $byte
   */
  function shiftOut($dataPin, $clockPin, $order, $byte) {
  }
}