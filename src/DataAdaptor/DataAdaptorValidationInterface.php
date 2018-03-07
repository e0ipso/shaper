<?php

namespace Shaper\DataAdaptor;

use Shaper\Util\Context;

interface DataAdaptorValidationInterface {

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

  /**
   * The validator for the input data.
   *
   * @return \Shaper\Validator\ValidateableInterface
   */
  public function getInputValidator();

  /**
   * The validator for the internal data.
   *
   * @return \Shaper\Validator\ValidateableInterface
   */
  public function getInternalValidator();

  /**
   * The validator for the output data.
   *
   * @return \Shaper\Validator\ValidateableInterface
   */
  public function getOutputValidator();

}
