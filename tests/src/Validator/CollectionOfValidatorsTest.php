<?php

namespace Shaper\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Shaper\Validator\CollectionOfValidators;
use Shaper\Validator\InstanceofValidator;

/**
 * @package Shaper
 *
 * @coversDefaultClass \Shaper\Validator\CollectionOfValidators
 */
class CollectionOfValidatorsTest extends TestCase {

  /**
   * @covers ::__construct
   */
  public function test__construct() {
    $sut = new CollectionOfValidators(new InstanceofValidator(TestCase::class));
    $this->assertInstanceOf(CollectionOfValidators::class, $sut);
  }

  /**
   * @covers ::__construct
   * @expectedException \TypeError
   */
  public function test__constructError() {
    new CollectionOfValidators('IAmAFail');
  }

  /**
   * @covers ::isValid
   */
  public function testIsValid() {
    $sut = new CollectionOfValidators(new InstanceofValidator(\stdClass::class));
    $this->assertTrue($sut->isValid([new \stdClass(), new \stdClass()]));
    $this->assertFalse($sut->isValid([new \stdClass(), 'fail']));
    $this->assertFalse($sut->isValid(new \stdClass()));
  }

}
