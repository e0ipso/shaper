<?php

namespace Shaper;

use Shaper\Util\Context;

class TransformationsQueue extends \SplQueue implements TransformationInterface {

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
  public function inboundValidator() {
    /** @var \Shaper\TransformationInterface $first_transformation */
    $first_transformation = $this->bottom();
    return $first_transformation->inboundValidator();
  }

  /**
   * {@inheritdoc}
   */
  public function outboundValidator() {
    /** @var \Shaper\TransformationInterface $last_transformation */
    $last_transformation = $this->top();
    return $last_transformation->outboundValidator();
  }

}
