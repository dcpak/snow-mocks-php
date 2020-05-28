<?php

class SnowRescueService
{
    private $weatherForecastService;
    private $municipalServices;
    private $pressService;

    public function __construct(WeatherForecastService $weatherForecastService, MunicipalServices $municipalServices, PressService $pressService)
    {
        $this->weatherForecastService = $weatherForecastService;
        $this->municipalServices = $municipalServices;
        $this->pressService = $pressService;
    }

    public function checkForecastAndRescue(): void
    {
    }
}
