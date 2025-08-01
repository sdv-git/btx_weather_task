<?php
use \Bitrix\Main\ModuleManager;

class weather_forecast extends CModule
{
    public function __construct()
    {
        require("version.php");
        $this->MODULE_ID = "weather.forecast";
        $this->MODULE_NAME = "Погода OpenWeather";
        $this->MODULE_DESCRIPTION = "Модуль для получения погоды c OpenWeather и вывод через AJAX-компонент";
        $this->PARTNER_NAME = "Дмитрий Смирнов";
        $this->PARTNER_URI = "https://example.com";
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}