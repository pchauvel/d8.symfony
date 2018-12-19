<?php

namespace Drupal\annonce\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Routing\ResettableStackedRouteMatchInterface;

/**
 * Class AnnonceEventSubscriber.
 */
class AnnonceEventSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\Core\Messenger\MessengerInterface definition.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;
  /**
 * Drupal\Core\Session\AccountProxyInterface definition.
 *
 * @var \Drupal\Core\Session\AccountProxyInterface
 */
  protected $currentUser;
  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentRouteMatch;

  /**
   * Constructs a new AnnonceEventSubscriber object.
   */
  public function __construct(MessengerInterface $messenger, AccountProxyInterface $current_user, ResettableStackedRouteMatchInterface $current_route_match) {
    $this->messenger = $messenger;
    $this->currentUser = $current_user;
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events['kernel.request'] = ['request_callnack'];

    return $events;
  }

  /**
   * This method is called whenever the kernel.request event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function request_callnack(Event $event) {
    $route = $this->currentRouteMatch->getRouteName();
    if($route == 'entity.annonce.canonical'){
      $this->messenger->addMessage('Ceci est une annonce.', 'status', TRUE);
    }


  }

}
