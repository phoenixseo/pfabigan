<?php

namespace Drupal\media_entity_pinterest\Plugin\Validation\Constraint;

use Drupal\media_entity\EmbedCodeValueTrait;
use Drupal\media_entity_pinterest\Plugin\MediaEntity\Type\Pinterest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the PinEmbedCode constraint.
 */
class PinEmbedCodeConstraintValidator extends ConstraintValidator {

  use EmbedCodeValueTrait;

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    $value = $this->getEmbedCode($value);
    if (!isset($value)) {
      return;
    }

    $matches = [];
    foreach (Pinterest::$validationRegexp as $pattern => $key) {
      // URLs will sometimes have urlencoding, so we decode for safety.
      if (preg_match($pattern, urldecode($value), $item_matches)) {
        $matches[] = $item_matches;
      }
    }

    if (empty($matches)) {
      $this->context->addViolation($constraint->message);
    }
  }

}
