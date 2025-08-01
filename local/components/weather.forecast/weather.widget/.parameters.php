<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$arComponentParameters = [
    'GROUPS' => [
        'BASE' => [
            'NAME' => 'Основные параметры',
        ],
        'CACHE_SETTINGS' => [
            'NAME' => 'Время кеширования',
        ],
    ],
    'PARAMETERS' => [
        'API_KEY' => [
            'NAME' => 'Ключ OpenWeatherMap API (обязательный!)',
            'TYPE' => 'STRING',
            'PARENT' => 'BASE',
        ],
        'CITY' => [
            'NAME' => 'Город',
            'TYPE' => 'STRING',
            'DEFAULT' => 'Moscow',
            'PARENT' => 'BASE',
        ],
        'UNITS' => [
            'NAME' => 'Единицы измерения',
            'TYPE' => 'LIST',
            'VALUES' => [
                "metric" => "°C",
                "imperial" => "°F",
            ],
            "DEFAULT" => "metric",
            'PARENT' => 'BASE',
        ],
        'CACHE_TIME' => [
            'NAME' => 'Время кеширования, сек.',
            'TYPE' => 'NUMBER',
            'DEFAULT' => '1800',
            'PARENT' => 'CACHE_SETTINGS',
        ],
    ],
];
?>