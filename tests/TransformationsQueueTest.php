<?php
/**
 * Created by PhpStorm.
 * User: e0ipso
 * Date: 27/02/2018
 * Time: 13:14
 */

namespace Shaper\Tests;

use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;
use Shaper\TransformationBase;
use Shaper\TransformationsQueue;
use Shaper\Util\Context;
use Shaper\Validator\AcceptValidator;
use Shaper\Validator\JsonSchemaValidator;

class TransformationFake2 extends TransformationBase {

  protected function validatorFactory($type) {
    switch ($type) {
      case static::INBOUND:
        return new JsonSchemaValidator(['type' => 'number'], new Validator());
      case static::OUTBOUND:
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

/**
 * @package Shaper
 *
 * @coversDefaultClass \Shaper\TransformationsQueue
 */
class TransformationsQueueTest extends TestCase {

  /**
   * @var \Shaper\TransformationsQueue
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
   * @covers ::inboundValidator
   */
  public function testInboundValidator() {
    $actual = $this->sut->inboundValidator('foo', $this->context)->toJSON();
    $this->assertEquals('{"type":"string"}', $actual);
  }

  /**
   * @covers ::outboundValidator
   */
  public function testOutboundValidator() {
    $actual = $this->sut->outboundValidator('foo', $this->context)->toJSON();
    $this->assertEquals('{"type":"array","items":{"type":"number"}}', $actual);
  }

}
