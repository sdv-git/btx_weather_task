<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
class WeatherWidgetComponent extends CBitrixComponent
{
    /**
     * Проверяем обязательный параметр
     * @return void
     * @throws \Bitrix\Main\ArgumentException
     */
    protected function checkParams()
    {
        if (empty($this->arParams['API_KEY'])) {
            throw new \Bitrix\Main\ArgumentException('В настройках компонента Погода не задан API ключ');
        }
    }
    /**
     * Подготавливаем входные параметры
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): ?array
    {
        $arParams['API_KEY'] ??= '';
        $arParams['CITY'] ??= 'Moscow';
        $arParams['UNITS'] ??= 'metric';

        return $arParams;
    }

    /**
     * Основной метод выполнения компонента
     * @return void
     */
    public function executeComponent() : void
    {
        try {
            $this->checkParams();
            // остальной код
        } catch (\Bitrix\Main\ArgumentException $e) {
            ShowError($e->getMessage());
            return;
        }
        // Кешируем вывод
        if ($this->startResultCache(false))
        {
            // получаем данные из модуля
            $this->initResult();

            // Если ничего не найдено или ошибка, отменяем кеширование
            if (empty($this->arResult))
            {
                $this->abortResultCache();
                ShowError('Не удалось получить сведения о погоде');

                return;
            }
            $this->prepareResult(); //Форматируем полученные значения для шаблона

            $this->includeComponentTemplate();
        }
    }
    /**
     * Вызов метода получения погоды из модуля
     * @return void
     * @throws \Bitrix\Main\LoaderException
     */
    private function initResult(): void
    {
        \Bitrix\Main\Loader::includeModule('weather.forecast');

        $weather = \Weather\Forecast\Weather::getCurrentWeather(
            $this->arParams['API_KEY'],
            $this->arParams['CITY'],
            $this->arParams['UNITS'],
        );

        if (!empty($weather)){
            $this->arResult['weatherApi'] = $weather;
        }
    }

    /**
     * Подготовка значений с форматированием и единицами
     * @return void
     */
    private function prepareResult(): void
    {
        $this->arResult['temperature'] = $this->arResult['weatherApi']['main']['temp'];  //температура
        $this->arResult['humidity'] = $this->arResult['weatherApi']['main']['humidity']; //влажность
        $this->arResult['pressure_mmHg'] = round($this->arResult['weatherApi']['main']['pressure'] * 0.75006); //давление в мм. рт. ст.

        $this->arResult['temperature_F'] = ($this->arResult['temperature'] > 0 ? '+' : '')
            . $this->arResult['temperature']
            . ($this->arParams['UNITS'] == 'metric' ? '°C' : '°F'); //форматированная температура
        $this->arResult['humidity_F'] = $this->arResult['humidity'] . '%'; //форматированная влажность
        $this->arResult['pressure_mmHg_F'] = $this->arResult['pressure_mmHg'] . ' мм рт. ст.'; //форматированное давление
    }
}