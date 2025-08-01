<?php
namespace Weather\Forecast;

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Web\HttpClient;
use mysql_xdevapi\Exception;

class Weather
{
    /**
     * Получить данные из кеша, либо сделать новый запрос погоды
     * @param string $apiKey
     * @param string $city
     * @param string $units
     * @return array|null
     */
    public static function getCurrentWeather(string $apiKey, string $city, string $units): ?array
    {
        // Настройки кеша, привязка его к городу и единицам
        $cacheTime = (int) Option::get('weather.forecast', 'API_RESPONSE_CACHE_TIME');
        $cacheId = "weather_data_{$city}_{$units}";
        $cacheDir = '/weather/forecast';
        $cache = Cache::createInstance();

        // Если есть актуальный кеш, то забираем из него данные
        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            // Данные есть в кэше
            return $cache->getVars();
        } elseif ($cache->startDataCache()) {
            // Данных нет в кеше - запрашиваем по API
            try {
                // Обращение к внешнему API
                $weatherData = self::fetchFromApi($apiKey, $city, $units);

                if ($weatherData['cod'] !== 200) {
                    throw new \Exception('Ошибка при получении данных подгоды от API');
                }
                // Кешируем результат
                $cache->endDataCache($weatherData);

                return $weatherData;
            } catch (\Exception $e) {
                $cache->abortDataCache(); // Если ошибка — не кешируем
                //TODO: надо записывать в логи
            }
        }
        return null; // fallback, если что-то пошло не так
    }

    /**
     * Запрос по API к OpenWeatherMap API
     * @param $apiKey
     * @param $city
     * @param $units
     * @return mixed|null
     */
    protected static function fetchFromApi($apiKey, $city, $units): ?array
    {
        $http = new HttpClient();
        $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&units={$units}&appid={$apiKey}";

        $response = $http->get($url);
        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);
        return $data ?? null;
    }
}