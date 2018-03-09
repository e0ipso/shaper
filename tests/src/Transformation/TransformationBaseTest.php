<?php

namespace Shaper\Tests\Transformation;

use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;
use Shaper\Transformation\TransformationBase;
use Shaper\Util\Context;
use Shaper\Validator\AcceptValidator;
use Shaper\Validator\JsonSchemaValidator;

class TransformationFake extends TransformationBase {
  public function getInputValidator() {
    return new JsonSchemaValidator(['type' => 'string'], new Validator());
  }
  public function getOutputValidator() {
    return new JsonSchemaValidator(['type' => 'number'], new Validator());
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
   * @covers ::getInputValidator
   * @covers ::getOutputValidator
   * @covers ::conformsToExpectedInputShape
   * @covers ::conformsToOutputShape
   * @expectedException \TypeError
   */
  public function testTransform() {
    $actual = $this->sut->transform('foo');
    $this->assertSame(42, $actual);
    $this->sut->transform([], $this->context);
  }

  /**
   * @covers ::transform
   * @expectedException \TypeError
   */
  public function testTransformBeforeError() {
    $this->sut->transform([], $this->context);
  }

  /**
   * @covers ::transform
   * @expectedException \TypeError
   */
  public function testTransformAfterError() {
    $sut = new TransformationFail();
    $sut->transform('foo', $this->context);
  }

}
