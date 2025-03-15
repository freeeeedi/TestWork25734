<?php

/**
 * Начало таблицы с погодой и поиском по городу
 */

 function beforeCityWeatherTableAction () {
    echo '<div id="city-search-container">
            <input type="text" id="city-search" placeholder="Введите город">
        </div>
        <table id="city-weather-table">
            <thead>
                <tr>
                    <th>Страна</th>
                    <th>Город</th>
                    <th>Температура</th>
                </tr>
            </thead>
            <tbody>';
}

add_action('before_city_weather_table','beforeCityWeatherTableAction');

/**
 * Конец таблицы с погодой
 */

function afterCityWeatherTableAction () {
    echo '</tbody>
        </table>';
}

add_action('after_city_weather_table','afterCityWeatherTableAction');