<?php

require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-content/themes/'.  get_stylesheet() . '/api/weather/WeatherService.php');

function ajax_search_cities() {

    global $wpdb;
    $weatherService = new WeatherService();
    $search = sanitize_text_field($_POST['search']);
    $query = $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = 'cities' AND post_status = 'publish' AND post_title LIKE %s", '%' . $wpdb->esc_like($search) . '%');

    $query = "SELECT posts.ID, posts.post_title, terms.name AS country 
              FROM {$wpdb->posts} AS posts
              JOIN {$wpdb->term_relationships} AS term_rel ON posts.ID = term_rel.object_id
              JOIN {$wpdb->term_taxonomy} AS term_tax ON term_rel.term_taxonomy_id = term_tax.term_taxonomy_id
              JOIN {$wpdb->terms} AS terms ON term_tax.term_id = terms.term_id
              WHERE posts.post_type = 'cities' AND posts.post_status = 'publish'" ;

    if (!empty($search)) {
        $query .= $wpdb->prepare(" AND posts.post_title LIKE %s", '%' . $wpdb->esc_like($search) . '%');
    }

    $cities = $wpdb->get_results($query);
    $results = [];

    foreach ($cities as $city) {
        $latitude = get_post_meta($city->ID, 'latitude', true);
        $longitude = get_post_meta($city->ID, 'longitude', true);
        $temperature = $weatherService->getTemperature((float) $latitude, (float) $longitude);

        $results[] = [
            'country'     => $city->country,
            'post_title'  => $city->post_title,
            'temperature' => $temperature
        ];
    }

    wp_send_json($results);
}
