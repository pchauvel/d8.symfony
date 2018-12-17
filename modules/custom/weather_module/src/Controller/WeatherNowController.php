<?php
namespace Drupal\weather_module\Controller;

use Symfony\Component\HttpFoundation\Response;

class WeatherNowController{
    public function content($cityId)
    {
        return array(
            '#title' => 'Weather',
            '#markup' => $city,

        );
    }
}