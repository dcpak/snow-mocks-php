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
      if($this->weatherForecastService->getAverageTemperatureInCelsius() < 0)
        $this->municipalServices->sendSander();
      if($this->weatherForecastService->getSnowFallHeightInMM() > 3)
        $this->municipalServices->sendSnowplow();
    }
}
