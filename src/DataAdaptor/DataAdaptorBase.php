<?php

namespace Shaper\DataAdaptor;

use Shaper\Util\Context;

/**
 * Transformation pair that can act as an adaptor between systems.
 *
 * Makes sure that data leaving A transforms into format B, and data arriving
 * from B complies with the format requirements of A.
 *
 * @package Shaper
 */
abstract class DataAdaptorBase implements DataAdaptorInterface, DataAdaptorValidationInterface {

  /**
   * {@inheritdoc}
   */
  public function transformIncomingData($data, Context $context) {
    if (!$this->conformsToExpectedInputShape($data, $context)) {
      throw new \TypeError(sprintf('Adaptor %s received invalid input data.', __CLASS__));
    }
    $output = $this->doTransformIncomingData($data, $context);
    if (!$this->conformsToInternalShape($output, $context)) {
      throw new \TypeError(sprintf('Adaptor %s returned invalid output data.', __CLASS__));
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function transformOutgoingData($data, Context $context) {
    if (!$this->conformsToInternalShape($data, $context)) {
      throw new \TypeError(sprintf('Adaptor %s received invalid input data.', __CLASS__));
    }
    $output = $this->doTransformOutgoingData($data, $context);
    if (!$this->conformsToOutputShape($output, $context)) {
      throw new \TypeError(sprintf('Adaptor %s returned invalid output data.', __CLASS__));
    }
    return $output;
  }

  /**
   * Transforms incoming data into another shape.
   *
   * This method will validate data coming in and going out using validators.
   *
   * @param mixed $data
   *   The data to transform.
   * @param \Shaper\Util\Context $context
   *   Additional information that will affect how the data is transformed.
   *
   * @return mixed
   *   The data in the new shape.
   *
   * @throws \TypeError
   *   When the transformation cannot be applied.
   */
  abstract protected function doTransformIncomingData($data, Context $context);

  /**
   * Transforms outgoing data into another shape.
   *
   * This method will validate data coming in and going out using validators.
   *
   * @param mixed $data
   *   The data to transform.
   * @param \Shaper\Util\Context $context
   *   Additional information that will affect how the data is transformed.
   *
   * @return mixed
   *   The data in the new shape.
   *
   * @throws \TypeError
   *   When the transformation cannot be applied.
   */
  abstract protected function doTransformOutgoingData($data, Context $context);

}
