<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
//$arResult['weatherApi'] - Полный ответ OpenWeatherMap API
//неформатированные(числовые) значения доступны без суфикса _F, например $arResult['temperature']?>
Прогноз погоды в <?=$arParams['CITY'] ?>
🌡 Температура: <?=$arResult['temperature_F'] ?>
💧 Влажность: <?=$arResult['humidity_F']?>
🌀 Давление: <?=$arResult['pressure_mmHg_F']?>