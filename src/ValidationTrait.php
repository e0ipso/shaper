<?php

namespace Shaper;

use Shaper\Util\Context;

trait ValidationTrait {

  /**
   * {@inheritdoc}
   */
  public function isApplicable($data, Context $context) {
    return $this->validatorFactory(static::BEFORE)->isValid($data);
  }

  /**
   * {@inheritdoc}
   */
  public function conformsToShape($data, Context $context) {
    return $this->validatorFactory(static::AFTER)->isValid($data);
  }

}
