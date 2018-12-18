<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 2018-12-17
 * Time: 17:19
 */

namespace Drupal\weather_module;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WeatherApi {

  /** string */
  private $appId;

  /**
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  private $session;

  public function __construct(
    $appId,
    SessionInterface $session,
    EventDispatcherInterface $eventDispatcher
  ) {
    $this->appId = $appId;
    $this->session = $session;
    $this->eventDispatcher = $eventDispatcher;

  }

  public function getWeatherInformation($cityId) {

    $url = sprintf(
      'https://api.openweathermap.org/data/2.5/weather?id=%s&appid=%s&units=metric',
      $cityId,
      $this->appId
    );
    $result = file_get_contents($url);
    $this->eventDispatcher->dispatch('weather_api.get');

    return json_decode($result, TRUE);
  }
}
