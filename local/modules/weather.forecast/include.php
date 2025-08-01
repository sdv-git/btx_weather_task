<?php
\Bitrix\Main\Loader::registerAutoLoadClasses(
    "weather.forecast",
    [
        "Weather\\Forecast\\Weather" => "lib/weather.php",
    ]
);