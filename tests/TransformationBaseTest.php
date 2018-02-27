<?php

namespace Shaper\Tests;

use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Shaper\TransformationBase;
use Shaper\Util\Context;
use Shaper\Validator\AcceptValidator;
use Shaper\Validator\JsonSchemaValidator;

class TransformationFake extends TransformationBase {

  protected function validatorFactory($type) {
    switch ($type) {
      case static::INBOUND:
        return new JsonSchemaValidator(['type' => 'string'], new Validator());
      case static::OUTBOUND:
        return new JsonSchemaValidator(['type' => 'number'], new Validator());
      default:
        return new AcceptValidator();
    }
  }

  protected function doTransform($data, Context $context) {
    return 42;
  }

}

class TransformationFail extends TransformationFake {
  protected function doTransform($data, Context $context) {
    return 'bar';
  }
}

/**
 * @package Shaper
 *
 * @coversDefaultClass \Shaper\TransformationBase
 */
class TransformationBaseTest extends TestCase {

  /**
   * @var \Shaper\Tests\TransformationFake
   */
  protected $sut;

  /**
   * @var \Shaper\Util\Context
   */
  protected $context;

  protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */ {
    parent::setUp();
    $this->sut = new TransformationFake();
    $this->context = new Context();
  }

  /**
   * @covers ::transform
   */
  public function testTransform() {
    $actual = $this->sut->transform('foo', $this->context);
    $this->assertSame(42, $actual);
    $this->expectException(\TypeError::class);
    $this->sut->transform([], $this->context);
  }

  /**
   * @covers ::transform
   */
  public function testTransformInboundError() {
    $this->expectException(\TypeError::class);
    $this->sut->transform([], $this->context);
  }

  /**
   * @covers ::transform
   */
  public function testTransformOutboundError() {
    $sut = new TransformationFail();
    $this->expectException(\TypeError::class);
    $sut->transform('foo', $this->context);
  }

  /**
   * @covers ::inboundValidator
   */
  public function testInboundValidator() {
    $validator = $this->sut->inboundValidator();
    $this->assertInstanceOf(JsonSchemaValidator::class, $validator);
  }

  /**
   * @covers ::outboundValidator
   */
  public function testOutboundValidator() {
    $validator = $this->sut->outboundValidator();
    $this->assertInstanceOf(JsonSchemaValidator::class, $validator);
  }

}
