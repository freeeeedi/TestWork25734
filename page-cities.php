<?php

/**
*
* Template Name: cities
*
*/

get_header();

require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-content/themes/'.  get_stylesheet() . '/api/weather/WeatherService.php');

global $wpdb;
$weatherService = new WeatherService();

do_action('before_city_weather_table');

$query = "SELECT posts.ID, posts.post_title, terms.name AS country 
        FROM {$wpdb->posts} AS posts
        JOIN {$wpdb->term_relationships} AS term_rel ON posts.ID = term_rel.object_id
        JOIN {$wpdb->term_taxonomy} AS term_tax ON term_rel.term_taxonomy_id = term_tax.term_taxonomy_id
        JOIN {$wpdb->terms} AS terms ON term_tax.term_id = terms.term_id
        WHERE posts.post_type = 'cities' AND posts.post_status = 'publish'" ;

$cities = $wpdb->get_results($query);

?>

<?php foreach ($cities as $city): ?>
    <?php 
    $latitude = get_post_meta($city->ID, 'latitude', true);
    $longitude = get_post_meta($city->ID, 'longitude', true);
    $temperature = $weatherService->getTemperature((float) $latitude, (float) $longitude);
    ?>
    <tr>
        <td><?php echo esc_html($city->country); ?></td>
        <td><?php echo esc_html($city->post_title); ?></td>
        <td><?php echo esc_html($temperature); ?>°C</td>
    </tr>
<?php endforeach; ?>

<?php do_action('after_city_weather_table'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('city-search');
    searchInput.addEventListener('input', function () {
        let search = this.value;
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=search_cities&search=' + encodeURIComponent(search)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            let tableBody = document.querySelector('#city-weather-table tbody');
            tableBody.innerHTML = '';
            data.forEach(city => {
                let row = `<tr>
                    <td>${city.country}</td>
                    <td>${city.post_title}</td>
                    <td>${city.temperature}°C</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        });
    });
});
</script>

<?php

get_footer();
?>