<?php

namespace Shaper\Tests\Validator;

use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;
use Shaper\Validator\JsonSchemaValidator;

/**
 * @package Shaper
 *
 * @coversDefaultClass \Shaper\Validator\JsonSchemaValidator
 */
class JsonSchemaValidatorTest extends TestCase {

  /**
   * @covers ::__construct
   * @covers ::setValidator
   */
  public function test__construct() {
    $sut = new JsonSchemaValidator(NULL, new Validator());
    $this->assertInstanceOf(JsonSchemaValidator::class, $sut);
  }

  /**
   * @covers ::toJSON
   */
  public function testToJSON() {
    $sut = new JsonSchemaValidator(['type' => 'string'], new Validator());
    $this->assertEquals('{"type":"string"}', $sut->toJSON());
  }

  /**
   * @covers ::isValid
   * @covers ::setValidator
   */
  public function testIsValid() {
    $sut = new JsonSchemaValidator(['type' => 'number']);
    $sut->setValidator(new Validator());
    $this->assertTrue($sut->isValid(42));
    $this->assertFalse($sut->isValid('not true'));
    $this->assertSame('String value found, but a number is required', $sut->getErrors()[0]['message']);
  }

  /**
   * @covers ::isValid
   * @expectedException \InvalidArgumentException
   */
  public function testIsValidError() {
    $sut = new JsonSchemaValidator(['type' => 'number']);
    $this->assertTrue($sut->isValid(NULL));
  }

  /**
   * @covers ::__sleep
   * @covers ::__wakeup
   */
  public function testSerialize() {
    $sut = new JsonSchemaValidator(['type' => 'number'], new Validator());
    $serialized = serialize($sut);
    $sut = unserialize($serialized);
    $this->assertInstanceOf(JsonSchemaValidator::class, $sut);
  }

}
