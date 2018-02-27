<?php

namespace Shaper\Validator;

use JsonSchema\Validator;

class JsonSchemaValidator implements ValidateableInterface {

  /**
   * The JSON object with the schema.
   *
   * @var array
   */
  protected $schema;

  /**
   * The schema validator.
   *
   * @var \JsonSchema\Validator
   */
  protected $validator;

  /**
   * JsonSchema constructor.
   *
   * @param array $schema
   *   The schema.
   * @param \JsonSchema\Validator $validator
   *   The validator.
   */
  public function __construct(array $schema = NULL, Validator $validator = NULL) {
    $this->schema = $schema;
    $this->validator = $validator;
  }

  /**
   * Sets the validator.
   *
   * @param \JsonSchema\Validator $validator
   *   The object that checks the JSON Schema.
   */
  public function setValidator(Validator $validator) {
    $this->validator = $validator;
  }

  /**
   * Transforms the schema into a JSON object.
   *
   * @return string
   *   The JSON object representation.
   */
  public function toJSON() {
    return json_encode($this->schema);
  }

  /**
   * {@inheritdoc}
   */
  public function isValid($data) {
    if (!$this->validator) {
      throw new \InvalidArgumentException('JSON Schema validator needs to be set using setValidator().');
    }
    return !$this->validator->check($data, $this->schema);
  }

  /**
   * Avoid serializing the validator.
   *
   * @return array
   *   The names of the properties to serialize.
   */
  public function __sleep() {
    return ['schema'];
  }

  /**
   * Re-attach a validator on de-serialization.
   */
  public function __wakeup() {
    $this->setValidator(new Validator());
  }

}
