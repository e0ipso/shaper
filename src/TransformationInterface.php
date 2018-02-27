<?php

namespace Shaper;

use Shaper\Util\Context;

/**
 * Interface for transformation classes.
 *
 * @package Shaper
 */
interface TransformationInterface {

  const INBOUND = 'inbound';
  const OUTBOUND = 'outbound';

  /**
   * Basic transformation from a shape into another shape.
   *
   * This method will validate data coming in and going out using validators.
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
  public function transform($data, Context $context);

  /**
   * Gets the validator that checks if the transformation can be applied.
   *
   * Typically this is one of three: a validator that accepts everything, a
   * validator based on a JSON Schema, a validator based on instanceof.
   *
   * @param string
   *   Checks if data coming in is acceptable.
   *
   * @return \Shaper\Validator\ValidateableInterface
   *   The validator.
   */
  public function inboundValidator();

  /**
   * Gets the validator that checks if the transformation yields a valid value.
   *
   * Typically this is one of three: a validator that accepts everything, a
   * validator based on a JSON Schema, a validator based on instanceof.
   *
   * @param string
   *   Checks if data going out is acceptable.
   *
   * @return \Shaper\Validator\ValidateableInterface
   *   The validator.
   */
  public function outboundValidator();

}
