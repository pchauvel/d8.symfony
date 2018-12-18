<?php
namespace Drupal\weather_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\weather_module\WeatherApi;

class WeatherModuleController extends ControllerBase {

  /** @var \Drupal\weather_module\WeatherApi */
  private $api;

  public function __construct(WeatherApi $api) {

    $this->api = $api;
  }

  public static function create(ContainerInterface $container) {
    return new self($container->get('weather_module.api'));
  }

  public function homepage() {
    return [
      '#title' => 'Weather',
      '#markup' => 'Homepage',
    ];
  }

  public function showWeather($cityId) {

    return [
      '#title' => 'Weather info',
      '#theme' => 'weather_show',
      '#weather' => $this->api->getWeatherInformation($cityId),
    ];
  }
}
