<?php

namespace Drupal\harbourmaster\Responses;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransparentPixelResponse.
 *
 * @package Drupal\harbourmaster\Responses
 */
class TransparentPixelResponse extends Response {
  /**
   * Base 64 encoded contents for 1px transparent gif and png.
   *
   * @var string
   */
  const IMAGE_CONTENT =
    'R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==';

  /**
   * The response content type.
   *
   * @var string
   */
  const CONTENT_TYPE = 'image/gif';

  /**
   * TransparentPixelResponse constructor.
   */
  public function __construct() {
    $content = base64_decode(self::IMAGE_CONTENT);
    parent::__construct($content);
    $this->headers->set('Content-Type', self::CONTENT_TYPE);
    $this->setPrivate();
    $this->headers->addCacheControlDirective('no-cache', TRUE);
    $this->headers->addCacheControlDirective('must-revalidate', TRUE);
  }

}
