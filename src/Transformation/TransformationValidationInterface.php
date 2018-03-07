<?php

namespace Shaper\Transformation;

use Shaper\Util\Context;

interface TransformationValidationInterface {

  const BEFORE = 'before';
  const AFTER = 'after';

  /**
   * Checks if the data provided can be transformed by the validator.
   *
   * @param mixed $data
   *   The data to check.
   * @param \Shaper\Util\Context $context
   *   Additional information that will affect applicability.
   *
   * @return bool
   *   TRUE if the transformation can be used with the supplied data.
   */
  public function isApplicable($data, Context $context);

  /**
   * Checks if the transformed data conforms to the expected shape.
   *
   * @param mixed $data
   *   The data to check.
   * @param \Shaper\Util\Context $context
   *   Additional information that will affect applicability.
   *
   * @return bool
   *   TRUE if the transformed data conforms to the expected shape.
   */
  public function conformsToShape($data, Context $context);

}
