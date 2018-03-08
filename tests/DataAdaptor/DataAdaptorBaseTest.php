<?php

namespace Shaper\Tests\DataAdaptor;

use Shaper\DataAdaptor\DataAdaptorBase;
use PHPUnit\Framework\TestCase;
use Shaper\Util\Context;
use Shaper\Validator\AcceptValidator;

class DataAdaptorFake extends DataAdaptorBase {
  protected function doTransform($data, Context $context) {
    return $data->{$context['key']};
  }
  protected function doUndoTransform($data, Context $context) {
    return (object) [$context['key'] => $data, 'bar' => 'default'];
  }
  public function getInputValidator() {
    return new AcceptValidator();
  }

  public function getInternalValidator() {
    return new AcceptValidator();
  }

  public function getOutputValidator() {
    return new AcceptValidator();
  }
}

class DataAdaptorFake2 extends DataAdaptorFake {
  public function conformsToExpectedInputShape($data, Context $context) {
    return FALSE;
  }
}

class DataAdaptorFake3 extends DataAdaptorFake {
  public function conformsToInternalShape($data, Context $context) {
    return FALSE;
  }
}

class DataAdaptorFake4 extends DataAdaptorFake {
  public function conformsToOutputShape($data, Context $context) {
    return FALSE;
  }
}

/**
 * @package Shaper
 *
 * @coversDefaultClass \Shaper\DataAdaptor\DataAdaptorBase
 */
class DataAdaptorBaseTest extends TestCase {

  /**
   * @var \Shaper\Tests\DataAdaptor\DataAdaptorFake
   */
  protected $sut;

  /**
   * @var \Shaper\Util\Context
   */
  protected $context;

  protected function setUp() {
    parent::setUp();
    $this->sut = new DataAdaptorFake();
    $this->context = new Context();
    $this->context['key'] = 'lorem';
  }

  /**
   * @covers ::transform
   * @covers ::conformsToExpectedInputShape
   * @covers ::conformsToInternalShape
   */
  public function testtransform() {
    $data = new \stdClass();
    $data->lorem = 'caramba!';
    $actual = $this->sut->transform($data, $this->context);
    $this->assertSame('caramba!', $actual);
  }

  /**
   * @covers ::transform
   */
  public function testtransformErrorInput() {
    $sut = new DataAdaptorFake2();
    $data = new \stdClass();
    $data->lorem = 'caramba!';
    $this->expectException(\TypeError::class);
    $sut->transform($data, $this->context);
  }

  /**
   * @covers ::transform
   */
  public function testtransformErrorOutput() {
    $sut = new DataAdaptorFake3();
    $data = new \stdClass();
    $data->lorem = 'caramba!';
    $this->expectException(\TypeError::class);
    $sut->transform($data, $this->context);
  }

  /**
   * @covers ::undoTransform
   * @covers ::conformsToInternalShape
   * @covers ::conformsToOutputShape
   */
  public function testundoTransform() {
    $actual = $this->sut->undoTransform('caramba!', $this->context);
    $expected = new \stdClass();
    $expected->lorem = 'caramba!';
    $expected->bar = 'default';
    $this->assertEquals($expected, $actual);
  }

  /**
   * @covers ::undoTransform
   */
  public function testundoTransformErrorInput() {
    $sut = new DataAdaptorFake3();
    $expected = new \stdClass();
    $expected->lorem = 'caramba!';
    $expected->bar = 'default';
    $this->expectException(\TypeError::class);
    $sut->undoTransform('caramba!', $this->context);
  }

  /**
   * @covers ::undoTransform
   */
  public function testundoTransformErrorOutput() {
    $sut = new DataAdaptorFake4();
    $expected = new \stdClass();
    $expected->lorem = 'caramba!';
    $expected->bar = 'default';
    $this->expectException(\TypeError::class);
    $sut->undoTransform('caramba!', $this->context);
  }

}
