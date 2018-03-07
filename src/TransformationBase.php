<?php

namespace Shaper;

use Shaper\Util\Context;

/**
 * Base implementation for transformation classes.
 *
 * @package Shaper
 */
abstract class TransformationBase implements TransformationInterface, TransformationValidationInterface {

  use ValidationTrait;

  /**
   * {@inheritdoc}
   */
  public function transform($data, Context $context) {
    if (!$this->isApplicable($data, $context)) {
      throw new \TypeError(sprintf('Transformation %s received invalid input data.', __CLASS__));
    }
    $output = $this->doTransform($data, $context);
    if (!$this->conformsToShape($output, $context)) {
      throw new \TypeError(sprintf('Transformation %s returned invalid output data.', __CLASS__));
    }
    return $output;
  }

  /**
   * Gets the validator that checks if the transformation can be applied.
   *
   * Typically this is one of three: a validator that accepts everything, a
   * validator based on a JSON Schema, a validator based on instanceof.
   *
   * @param string
   *   Either static::BEFORE or static::AFTER. The before validator checks
   *   if data coming in is acceptable, the after validator checks if data
   *   going out is acceptable.
   *
   * @return \Shaper\Validator\ValidateableInterface
   *   The validator.
   */
  abstract protected function validatorFactory($type);

  /**
   * Basic transformation from a shape into another shape.
   *
   * This method does not include validations since they are handled in the
   * calling method.
   *
   * @param mixed $data
   *   The data to transform.
   *
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
