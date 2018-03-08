<?php

namespace Shaper\DataAdaptor;

use Shaper\Transformation\TransformationInterface;
use Shaper\Util\Context;

/**
 * Transformation pair that can act as an adaptor between systems.
 *
 * Makes sure that data leaving A transforms into format B, and data arriving
 * from B complies with the format requirements of A.
 *
 * @package Shaper
 */
abstract class DataAdaptorBase implements TransformationInterface, ReversibleTransformationInterface, ReversibleTransformationValidationInterface {

  /**
   * {@inheritdoc}
   */
  public function transform($data, Context $context) {
    if (!$this->conformsToExpectedInputShape($data, $context)) {
      throw new \TypeError(sprintf('Adaptor %s received invalid input data.', __CLASS__));
    }
    $output = $this->doTransform($data, $context);
    if (!$this->conformsToInternalShape($output, $context)) {
      throw new \TypeError(sprintf('Adaptor %s returned invalid output data.', __CLASS__));
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function undoTransform($data, Context $context) {
    if (!$this->conformsToInternalShape($data, $context)) {
      throw new \TypeError(sprintf('Adaptor %s received invalid input data.', __CLASS__));
    }
    $output = $this->doUndoTransform($data, $context);
    if (!$this->conformsToOutputShape($output, $context)) {
      throw new \TypeError(sprintf('Adaptor %s returned invalid output data.', __CLASS__));
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function conformsToExpectedInputShape($data, Context $context) {
    return $this->getInputValidator()->isValid($data);
  }

  /**
   * {@inheritdoc}
   */
  public function conformsToInternalShape($data, Context $context) {
    return $this->getInternalValidator()->isValid($data);
  }

  /**
   * {@inheritdoc}
   */
  public function conformsToOutputShape($data, Context $context) {
    return $this->getOutputValidator()->isValid($data);
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
  abstract protected function doTransform($data, Context $context);

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
  abstract protected function doUndoTransform($data, Context $context);

}
