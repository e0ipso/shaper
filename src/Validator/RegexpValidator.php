<?php

namespace Shaper\Validator;

use JsonSchema\Validator;

class RegexpValidator implements ValidateableInterface {

  /**
   * The name of the class or interface the data must comply.
   *
   * @var \Shaper\Validator\JsonSchemaValidator
   */
  protected $stringValidator;

  /**
   * The regular expression.
   *
   * @var string
   */
  protected $regexp;

  /**
   * InstanceofValidator constructor.
   *
   * @param string $regexp
   *   The regular expression (without delimiters) to check.
   */
  public function __construct($regexp) {
    $this->regexp = $regexp;
    $this->stringValidator = new JsonSchemaValidator(['type' => 'string'], new Validator());
  }

  /**
   * {@inheritdoc}
   */
  public function isValid($data) {
    return $this->stringValidator->isValid($data) &&
      preg_match('@' . $this->regexp . '@', $data);
  }

}
