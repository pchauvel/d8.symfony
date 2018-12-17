<?php
namespace Drupal\weather_module\Controller;

use Symfony\Component\HttpFoundation\Response;

class WeatherNowController{
    const APP_ID = '524901';
    public function content($cityId)
    {
       $url = sprintf(
            'https://samples.openweathermap.org/data/2.5/forecast?id=%s&appid=%s',
            $cityId,
            self::APP_ID
        );
        $result = file_get_contents($url);
        $json_result = json_decode($result,true);
        dump($json_result);
        die;
        return array(
            '#title' => 'Weather',
            '#markup' => $result,

        );
    }
}