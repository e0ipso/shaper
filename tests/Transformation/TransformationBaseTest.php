<?php

namespace Shaper\Tests\Transformation;

use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;
use Shaper\Transformation\TransformationBase;
use Shaper\Util\Context;
use Shaper\Validator\AcceptValidator;
use Shaper\Validator\JsonSchemaValidator;

class TransformationFake extends TransformationBase {

  protected function validatorFactory($type) {
    switch ($type) {
      case static::BEFORE:
        return new JsonSchemaValidator(['type' => 'string'], new Validator());
      case static::AFTER:
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
 * @coversDefaultClass \Shaper\Transformation\TransformationBase
 */
class TransformationBaseTest extends TestCase {

  /**
   * @var \Shaper\Tests\Transformation\TransformationFake
   */
  protected $sut;

  /**
   * @var \Shaper\Util\Context
   */
  protected $context;

  protected function setUp() {
    parent::setUp();
    $this->sut = new TransformationFake();
    $this->context = new Context();
  }

  /**
   * @covers ::transform
   * @covers ::isApplicable
   * @covers ::conformsToShape
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
  public function testTransformBeforeError() {
    $this->expectException(\TypeError::class);
    $this->sut->transform([], $this->context);
  }

  /**
   * @covers ::transform
   */
  public function testTransformAfterError() {
    $sut = new TransformationFail();
    $this->expectException(\TypeError::class);
    $sut->transform('foo', $this->context);
  }

}
