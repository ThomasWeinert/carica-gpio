<?php
namespace Carica\Gpio {

  require_once(__DIR__.'/bootstrap.php');

  class PinTest extends \PHPUnit_Framework_TestCase {

    public function testConstructor() {
      $commands = $this->getMockBuilder(Commands::class)->getMock();
      $pin = new Pin(
        $commands, 42, [ Board::GPIO_PIN_MAPPING => 21, Pin::MODE_INPUT => 1, Pin::MODE_PWM => 255 ]
      );
      $this->assertEquals(42, $pin->getNumber());
      $this->assertEquals(21, $pin->getGpioNumber());
      $this->assertTrue($pin->supports(Pin::MODE_INPUT));
      $this->assertTrue($pin->supports(Pin::MODE_PWM));
      $this->assertFalse($pin->supports(Pin::MODE_OUTPUT));
    }
  }
}


