<?php

namespace Shaper\Transformation;

use Shaper\Util\Context;

trait TransformationTransformerTrait {

  /**
   * {@inheritdoc}
   */
  public function transform($data, Context $context = NULL) {
    if (!isset($context)) {
      $context = new Context();
    }
    if (!$this->conformsToExpectedInputShape($data, $context)) {
      /** @var \Shaper\Validator\ValidateableInterface $validator */
      $validator = $this->getInputValidator();
      $message = sprintf(
        'Adaptor %s received invalid input data: %s',
        __CLASS__,
        json_encode($validator->getErrors(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)
      );
      throw new \TypeError($message);
    }
    $output = $this->doTransform($data, $context);
    if (!$this->conformsToOutputShape($output, $context)) {
      /** @var \Shaper\Validator\ValidateableInterface $validator */
      $validator = $this->getOutputValidator();
      $message = sprintf(
        'Adaptor %s returned invalid output data: %s',
        __CLASS__,
        json_encode($validator->getErrors(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)
      );
      throw new \TypeError($message);
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
