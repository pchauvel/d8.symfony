<?php

namespace Drupal\annonce\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Routing\ResettableStackedRouteMatchInterface;
use \Drupal\Core\Database\Connection;

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
  public function __construct(MessengerInterface $messenger, AccountProxyInterface $current_user, ResettableStackedRouteMatchInterface $current_route_match, Connection $database) {
    $this->messenger = $messenger;
    $this->currentUser = $current_user;
    $this->currentRouteMatch = $current_route_match;
    $this->database = $database;
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
      $this->database->insert('annonce_history')
        ->fields([
          'uid' => $this->currentUser->id(),
          'aid' => $this->currentRouteMatch->getParameter('annonce')->id(),
          'date' => REQUEST_TIME,
        ])
        ->execute();
    } else {
      $this->messenger->addMessage('Event for ' . $this->currentUser->getDisplayName(), 'status', TRUE);
    }




  }

}
