<?php

class WeatherService {
    private string $baseUrl = "https://api.open-meteo.com/v1/forecast";
        
    /**
     * Получение температуры
     *
     * @param  float $latitude
     * @param  float $longitude
     * @param  string $temperatureUnit
     * @return string
     */
    public function getTemperature(float $latitude, float $longitude, string $temperatureUnit = "celsius"): string {

        $url = "$this->baseUrl?latitude=$latitude&longitude=$longitude&current_weather=true&temperature_unit=$temperatureUnit";
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return 'N/A';
        }

        $weather = json_decode(wp_remote_retrieve_body($response));

        return $weather->current_weather->temperature ?? 'N/A';

    }
}