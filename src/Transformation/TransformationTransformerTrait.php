<?php

namespace Shaper\Transformation;

use Shaper\Util\Context;

trait TransformationTransformerTrait {

  /**
   * {@inheritdoc}
   */
  public function transform($data, Context $context) {
    if (!$this->conformsToExpectedInputShape($data, $context)) {
      throw new \TypeError(sprintf('Transformation %s received invalid input data.', __CLASS__));
    }
    $output = $this->doTransform($data, $context);
    if (!$this->conformsToOutputShape($output, $context)) {
      throw new \TypeError(sprintf('Transformation %s returned invalid output data.', __CLASS__));
    }
    return $output;
  }

  /**
   * Basic transformation from a shape into another shape.
   *
   * This method does not include validations since they are handled in the
   * calling method.
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

}
