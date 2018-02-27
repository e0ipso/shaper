<?php

namespace Shaper\Validator;

class InstanceofValidator implements ValidateableInterface {

  /**
   * The name of the class or interface the data must comply.
   *
   * @var string
   */
  protected $supportedClassOrInterface;

  /**
   * InstanceofValidator constructor.
   *
   * @param string $supported_class_or_interface
   *   The class or interface. It must exist.
   *
   * @throws \TypeError
   *   If the class or interface does not exist.
   */
  public function __construct($supported_class_or_interface) {
    if (
      !class_exists($supported_class_or_interface) &&
      !interface_exists($supported_class_or_interface)
    ) {
      $message = sprintf('Class or interface %s does not exist.', $supported_class_or_interface);
      throw new \TypeError($message);
    }
    $this->supportedClassOrInterface = $supported_class_or_interface;
  }

  /**
   * {@inheritdoc}
   */
  public function isValid($data) {
    return is_a($data, $this->supportedClassOrInterface);
  }

}
