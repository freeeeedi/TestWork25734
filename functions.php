<?php

/**
 * Регистрация типа записи "Cities"
 *
 * @return void
 */
function registerCitiesPostType() {

    $args = array(
        'labels'      => array(
            'name'          => 'Cities',
            'singular_name' => 'City'
        ),
        'public'      => true,
        'supports'    => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
    );

    register_post_type('cities', $args);
}

add_action('init', 'registerCitiesPostType');

/**
 * Регистрация таксономии "Countries"
 *
 * @return void
 */
function registerCountriesTaxonomy() {

    $args = array(
        'labels'            => array('name' => 'Countries', 'singular_name' => 'Country'),
        'public'            => true,
        'hierarchical'      => true,
    );

    register_taxonomy('countries', 'cities', $args);
}

add_action('init', 'registerCountriesTaxonomy');

/**
 * Добавление метабоксов для широты и долготы
 *
 * @return void
 */
function addCityMetabox() {

    add_meta_box('city_location', 'City Location', 'cityMetaboxCallback', 'cities', 'normal', 'high');

}

add_action('add_meta_boxes', 'addCityMetabox');

function cityMetaboxCallback($post) {

    $latitude = get_post_meta($post->ID, 'latitude', true);
    $longitude = get_post_meta($post->ID, 'longitude', true);

    echo "<label>Latitude:</label> <input type='text' name='latitude' value='$latitude' /><br/>";
    echo "<label>Longitude:</label> <input type='text' name='longitude' value='$longitude' />";

}

/**
 * Сохранение значений полей широты и долготы в записи
 *
 * @param  mixed $post_id
 * @return void
 */
function saveCityMeta($post_id) {

    if (isset($_POST['latitude'])) {
        update_post_meta($post_id, 'latitude', (float) $_POST['latitude']);
    }
    if (isset($_POST['longitude'])) {
        update_post_meta($post_id, 'longitude', (float) $_POST['longitude']);
    }

}

add_action('save_post', 'saveCityMeta');

/**
 * Регистрация виджета для получения погоды
 */

require_once(__DIR__ . '/widgets/class-city-weather-widget.php');

add_action('widgets_init', function() { 
    register_widget('City_Weather_Widget'); 
    }
);

/**
 * Регистрация action "search_cities" для ajax-обработчика
 */

require_once(__DIR__ . '/ajax/search-cities.php');

add_action('wp_ajax_search_cities', 'ajax_search_cities');
add_action('wp_ajax_nopriv_search_cities', 'ajax_search_cities');

/**
 * Подключение произвольных хуков
 */

require_once(__DIR__ . '/custom-actions.php');