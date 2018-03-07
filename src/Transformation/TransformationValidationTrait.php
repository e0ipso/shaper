<?php

namespace Shaper\Transformation;

use Shaper\Util\Context;

trait TransformationValidationTrait {

  /**
   * {@inheritdoc}
   */
  public function conformsToInternalShape($data, Context $context) {
    return $this->getInputValidator()->isValid($data);
  }

  /**
   * {@inheritdoc}
   */
  public function conformsToOutputShape($data, Context $context) {
    return $this->getOutputValidator()->isValid($data);
  }

}
