[![Coverage Status](https://coveralls.io/repos/e0ipso/shaper/badge.svg?branch=master&service=github)](https://coveralls.io/github/e0ipso/shaper?branch=master) [![Build Status](https://travis-ci.org/e0ipso/shaper.svg?branch=master)](https://travis-ci.org/e0ipso/shaper)

# Shaper

Shaper is a simple library that enables type safe data transformations. You can either have simple
transformations or queued transformations.

## Simple Transformations

```php
use JsonSchema\Validator;
use Shaper\TransformationBase;
use Shaper\Util\Context;
use Shaper\Validator\JsonSchemaValidator;


class NumberToArray extends TransformationBase {

  protected function validatorFactory($type) {
    switch ($type) {
      case static::BEFORE:
        return new JsonSchemaValidator(['type' => 'number'], new Validator());
      case static::AFTER:
        $schema = ['type' => 'array', 'items' => ['type' => 'number']];
        return new JsonSchemaValidator($schema, new Validator());
    }
  }

  protected function doTransform($data, Context $context) {
    return [$context['keyName'] => $data];
  }

}

$t = new NumberToArray();
$t->transform(42, new Context(['keyName' => 'data'])); // ['data' => 42]
$t->transform(['foo']); // TypeError exception.
```

## Queued Transformations

```php
use JsonSchema\Validator;
use Shaper\TransformationBase;
use Shaper\Util\Context;
use Shaper\Validator\JsonSchemaValidator;
use Shaper\TransformationsQueue;

class ObjectToNumber extends TransformationBase {

  protected function validatorFactory($type) {
    switch ($type) {
      case static::BEFORE:
        return new InstanceofValidator(\stdClass::class);
      case static::AFTER:
        $schema = ['type' => 'number'];
        return new JsonSchemaValidator($schema, new Validator());
    }
  }

  protected function doTransform($data, Context $context) {
    return isset($data->value) ? $data->value : 0;
  }

}

$t = new TransformationsQueue();
$t->add(new ObjectToNumber());
$t->add(new NumberToArray());
$input = new \stdClass();
$input->value = 42;
$t->transform($input, new Context(['keyName' => 'data'])); // ['data' => 42]
```
