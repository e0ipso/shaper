<?php

namespace Shaper\Tests\DataAdaptor;

use Shaper\DataAdaptor\DataAdaptorBase;
use PHPUnit\Framework\TestCase;
use Shaper\Util\Context;
use Shaper\Validator\AcceptValidator;

class DataAdaptorFake extends DataAdaptorBase {
  protected function doTransformIncomingData($data, Context $context) {
    return $data->{$context['key']};
  }
  protected function doTransformOutgoingData($data, Context $context) {
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
   * @covers ::transformIncomingData
   * @covers ::conformsToExpectedInputShape
   * @covers ::conformsToInternalShape
   */
  public function testTransformIncomingData() {
    $data = new \stdClass();
    $data->lorem = 'caramba!';
    $actual = $this->sut->transformIncomingData($data, $this->context);
    $this->assertSame('caramba!', $actual);
  }

  /**
   * @covers ::transformIncomingData
   */
  public function testTransformIncomingDataErrorInput() {
    $sut = new DataAdaptorFake2();
    $data = new \stdClass();
    $data->lorem = 'caramba!';
    $this->expectException(\TypeError::class);
    $sut->transformIncomingData($data, $this->context);
  }

  /**
   * @covers ::transformIncomingData
   */
  public function testTransformIncomingDataErrorOutput() {
    $sut = new DataAdaptorFake3();
    $data = new \stdClass();
    $data->lorem = 'caramba!';
    $this->expectException(\TypeError::class);
    $sut->transformIncomingData($data, $this->context);
  }

  /**
   * @covers ::transformOutgoingData
   * @covers ::conformsToInternalShape
   * @covers ::conformsToOutputShape
   */
  public function testTransformOutgoingData() {
    $actual = $this->sut->transformOutgoingData('caramba!', $this->context);
    $expected = new \stdClass();
    $expected->lorem = 'caramba!';
    $expected->bar = 'default';
    $this->assertEquals($expected, $actual);
  }

  /**
   * @covers ::transformOutgoingData
   */
  public function testTransformOutgoingDataErrorInput() {
    $sut = new DataAdaptorFake3();
    $expected = new \stdClass();
    $expected->lorem = 'caramba!';
    $expected->bar = 'default';
    $this->expectException(\TypeError::class);
    $sut->transformOutgoingData('caramba!', $this->context);
  }

  /**
   * @covers ::transformOutgoingData
   */
  public function testTransformOutgoingDataErrorOutput() {
    $sut = new DataAdaptorFake4();
    $expected = new \stdClass();
    $expected->lorem = 'caramba!';
    $expected->bar = 'default';
    $this->expectException(\TypeError::class);
    $sut->transformOutgoingData('caramba!', $this->context);
  }

}
