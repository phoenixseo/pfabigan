<?php

namespace Drupal\password_policy\EventSubscriber;

use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Config\ConfigImporterEvent;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Drupal\user\Entity\User;

/**
 * Enforces password reset functionality.
 */
class PasswordPolicyEventSubscriber implements EventSubscriberInterface {

  /**
   * Event callback to look for users expired password.
   */
  public function checkForUserPasswordExpiration(GetResponseEvent $event) {
    $account = \Drupal::currentUser();
    // There needs to be an explicit check for non-anonymous or else
    // this will be tripped and a forced redirect will occur.
    if ($account->id() > 0) {
      /* @var $user \Drupal\user\UserInterface */
      $user = User::load($account->id());
      $request = \Drupal::request();

      $route_name = $request->attributes->get(RouteObjectInterface::ROUTE_NAME);
      $ignore_route = in_array($route_name, [
        'entity.user.edit_form',
        'system.ajax',
        'user.logout',
        'admin_toolbar_tools.flush',
      ]);

      $is_ajax = $request->headers->get('X_REQUESTED_WITH') == 'XMLHttpRequest';

      $user_expired = FALSE;
      if ($user->get('field_password_expiration')->get(0)) {
        $user_expired = $user->get('field_password_expiration')
          ->get(0)
          ->getValue();
        $user_expired = $user_expired['value'];
      }

      // TODO - Consider excluding admins here.
      if ($user_expired && !$ignore_route && !$is_ajax) {
        $url = new Url('entity.user.edit_form', ['user' => $user->id()]);
        $url = $url->setAbsolute(TRUE)->toString();
        $event->setResponse(new RedirectResponse($url));
        drupal_set_message(t('Your password has expired, please update it'), 'error');
      }
    }
  }

  /**
   * Updates password reset value for all users.
   *
   * @param \Drupal\Core\Config\ConfigImporterEvent $event
   *   The config importer event.
   */
  public function onConfigImport(ConfigImporterEvent $event) {
    $modules = $event->getConfigImporter()->getExtensionChangelist('module', 'install');

    if (!in_array('password_policy', $modules)) {
      return;
    }

    $timestamp = gmdate(DATETIME_DATETIME_STORAGE_FORMAT, REQUEST_TIME);

    /** @var \Drupal\user\UserInterface[] $users */
    $users = \Drupal::entityTypeManager()->getStorage('user')->loadMultiple();

    // @todo Get rid of updating all users.
    foreach ($users as $user) {
      if ($user->getAccountName() == NULL) {
        continue;
      }
      $user
        ->set('field_last_password_reset', $timestamp)
        ->set('field_password_expiration', '0')
        ->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // TODO - Evaluate if there is a better place to add this check.
    $events[KernelEvents::REQUEST][] = ['checkForUserPasswordExpiration'];
    $events[ConfigEvents::IMPORT][] = ['onConfigImport'];
    return $events;
  }

}
