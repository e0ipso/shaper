<?php

namespace Shaper\Validator;

interface ValidateableInterface {

  /**
   * Checks that the provided data is valid.
   *
   * @param mixed $data
   *   The data to validate.
   *
   * @return bool
   *   TRUE if the data is valid. FALSE otherwise.
   */
  public function isValid($data);

}
