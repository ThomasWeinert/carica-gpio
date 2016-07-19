<?php

namespace Carica\Gpio {

  require_once(__DIR__.'/bootstrap.php');

  class PinsTest extends \PHPUnit_Framework_TestCase {

    public function testConstructor() {
      $commands = $this->getMockBuilder(Commands::class)->getMock();
      $pins = new Pins(
        $commands,
        [
          21 => [
            Pin::MODE_OUTPUT => 2
          ]
        ]
      );
      $this->assertInstanceOf(Pin::class, $pins[21]);
      $this->assertTrue($pins[21]->supports(Pin::MODE_OUTPUT));
    }
  }
}
