<?php

namespace Shaper\DataAdaptor;

use Shaper\Util\Context;

trait DataAdaptorTransformerTrait {

  /**
   * {@inheritdoc}
   */
  public function transform($data, Context $context = NULL) {
    if (!isset($context)) {
      $context = new Context();
    }
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
  public function undoTransform($data, Context $context = NULL) {
    if (!isset($context)) {
      $context = new Context();
    }
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
