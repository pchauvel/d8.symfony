<?php


namespace Drupal\weather_module\event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WeatherApiSubscriber implements EventSubscriberInterface
{
  private $logger;

  public function __construct(LoggerChannelInterface $logger) {
    $this->logger = $logger;
  }

  /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return[
             'weather_api.get' => 'onWeatherApiGet',
        ];
    }
    public function onWeatherApiGet()
    {
      $this->logger->info('Weather API used');
    }
}