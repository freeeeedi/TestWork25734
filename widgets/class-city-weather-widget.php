<?php

require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-content/themes/'.  get_stylesheet() . '/api/weather/WeatherService.php');

class City_Weather_Widget extends WP_Widget {
    function __construct() {
        parent::__construct('city_weather_widget', 'City Weather Widget');
    }
    
    /**
     * widget
     *
     * @param  mixed $args
     * @param  mixed $instance
     * @return void
     */
    function widget($args, $instance) {

        $city_id = $instance['city_id'];
        $city_name = get_the_title($city_id);
        $latitude = get_post_meta($city_id, 'latitude', true);
        $longitude = get_post_meta($city_id, 'longitude', true);

        $weatherService = new WeatherService();

        $temperature = $weatherService->getTemperature($latitude, $longitude);
        
        echo "<h3>$city_name</h3><p>Температура: {$temperature}°C</p>";

    }
    
    /**
     * form
     *
     * @param  mixed $instance
     * @return void
     */
    function form($instance) {

        $city_id = $instance['city_id'] ?? '';
        $cities = get_posts(array('post_type' => 'cities', 'numberposts' => -1));

        echo '<label for="' . $this->get_field_id('city_id') . '">Выбор города:</label>'; 
        echo '<select name="' . $this->get_field_name('city_id') . '" id="' . $this->get_field_id('city_id') . '">';

        foreach ($cities as $city) {
            echo "<option value='{$city->ID}'" . selected($city_id, $city->ID, false) . ">{$city->post_title}</option>";
        }
        
        echo '</select>';

    }
       
    /**
     * update
     *
     * @param  mixed $new_instance
     * @param  mixed $old_instance
     * @return array
     */
    function update($new_instance, $old_instance) {
        return ['city_id' => $new_instance['city_id']];
    }
}