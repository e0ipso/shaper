<?php

namespace Shaper\Tests\Transformation;

use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;
use Shaper\Transformation\TransformationBase;
use Shaper\Transformation\TransformationsQueue;
use Shaper\Util\Context;
use Shaper\Validator\AcceptValidator;
use Shaper\Validator\JsonSchemaValidator;

class TransformationFake2 extends TransformationBase {

  protected function validatorFactory($type) {
    switch ($type) {
      case static::BEFORE:
        return new JsonSchemaValidator(['type' => 'number'], new Validator());
      case static::AFTER:
        $schema = ['type' => 'array', 'items' => ['type' => 'number']];
        return new JsonSchemaValidator($schema, new Validator());
      default:
        return new AcceptValidator();
    }
  }

  protected function doTransform($data, Context $context) {
    return [$data];
  }

}

class TransformationFail2 extends TransformationFake {

  protected function validatorFactory($type) {
    switch ($type) {
      case static::BEFORE:
      case static::AFTER:
        return new JsonSchemaValidator(['type' => 'number'], new Validator());
      default:
        return new AcceptValidator();
    }
  }

  protected function doTransform($data, Context $context) {
    return 'bar';
  }
}

/**
 * @package Shaper
 *
 * @coversDefaultClass \Shaper\Transformation\TransformationsQueue
 */
class TransformationsQueueTest extends TestCase {

  /**
   * @var \Shaper\Transformation\TransformationsQueue
   */
  protected $sut;

  /**
   * @var \Shaper\Util\Context
   */
  protected $context;

  protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */ {
    parent::setUp();
    $this->sut = new TransformationsQueue();
    $this->sut->push(new TransformationFake());
    $this->sut->push(new TransformationFake2());
    $this->context = new Context();
  }


  /**
   * @covers ::transform
   */
  public function testTransform() {
    $actual = $this->sut->transform('foo', $this->context);
    $this->assertEquals([42], $actual);
  }

  /**
   * @covers ::isApplicable
   */
  public function testIsApplicable() {
    $this->assertFalse($this->sut->isApplicable([], $this->context));
  }

  /**
   * @covers ::conformsToShape
   */
  public function testConformsToShape() {
    $this->assertFalse($this->sut->conformsToShape(new \stdClass(), $this->context));
  }

}
