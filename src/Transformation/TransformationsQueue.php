<?php

namespace Shaper\Transformation;

use Shaper\Util\Context;

class TransformationsQueue extends \SplQueue implements TransformationInterface, TransformationValidationInterface {

  use TransformationValidationTrait;

  /**
   * {@inheritdoc}
   */
  public function transform($data, Context $context) {
    $output = $data;
    foreach ($this as $transformation) {
      $output = $transformation->transform($output, $context);
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function getInputValidator() {
    /** @var \Shaper\Transformation\TransformationValidationInterface $first_transformation */
    $first_transformation = $this->bottom();
    return $first_transformation->getInputValidator();
  }

  /**
   * {@inheritdoc}
   */
  public function getOutputValidator() {
    /** @var \Shaper\Transformation\TransformationValidationInterface $last_transformation */
    $last_transformation = $this->top();
    return $last_transformation->getOutputValidator();
  }

}
