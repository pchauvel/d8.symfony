<?php
namespace Drupal\weather_module\Controller;

use Symfony\Component\HttpFoundation\Response;

class WeatherController{
    public function homepage()
    {
        // return new Response('Demo');
        return array(
            '#title' => 'Weather',
            '#markup' => 'Homepage',

        );
    }
}