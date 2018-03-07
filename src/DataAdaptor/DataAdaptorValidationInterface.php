<?php

namespace Shaper\DataAdaptor;

use Shaper\Util\Context;

interface DataAdaptorValidationInterface {

  const BEFORE_INCOMING = 'before-incoming';
  const INTERNAL = 'internal';
  const AFTER_OUTGOING = 'after-outgoing';

  /**
   * Checks if it can transform the provided data to the internal shape.
   *
   * @param mixed $data
   *   The incoming data.
   * @param \Shaper\Util\Context $context
   *   Additional information that will affect how the data is transformed.
   *
   * @return bool
   *   TRUE if the format is valid.
   */
  public function conformsToExpectedInputShape($data, Context $context);

  /**
   * Checks if the shape of the transformed data is valid for internal use.
   *
   * @param mixed $data
   *   The data in the internal format.
   * @param \Shaper\Util\Context $context
   *   Additional information that will affect how the data is transformed.
   *
   * @return bool
   *   TRUE if the format is valid.
   */
  public function conformsToInternalShape($data, Context $context);

  /**
   * Checks if the shape of the transformed data is valid for the outside world.
   *
   * @param mixed $data
   *   The data in the internal format.
   * @param \Shaper\Util\Context $context
   *   Additional information that will affect how the data is transformed.
   *
   * @return bool
   *   TRUE if the format is valid.
   */
  public function conformsToOutputShape($data, Context $context);

}
