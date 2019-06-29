<?php

namespace Drupal\media_expire\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {

    if ($route = $collection->get('entity.media.canonical')) {
      $route->setDefault('_controller', 'Drupal\media_expire\Controller\MediaViewController::view');

    }
  }

}
