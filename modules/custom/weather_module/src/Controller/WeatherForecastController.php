<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 17/12/2018
 * Time: 13:44
 */

namespace Drupal\weather_module\Controller;


class WeatherForecastController
{
    public function content($city)
    {
        $param = "524901";
        $api_call = "https://samples.openweathermap.org/data/2.5/forecast?id=".$param."&appid=b1b15e88fa797225412429c1c50c122a1";
        return array(
            '#title' => 'Forecast',
            '#markup' => $city,

        );
    }
}