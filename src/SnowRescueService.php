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
      try {
        if($this->weatherForecastService->getSnowFallHeightInMM() > 5)
          $this->municipalServices->sendSnowplow();
        if($this->weatherForecastService->getSnowFallHeightInMM() > 3)
          $this->municipalServices->sendSnowplow();
      } catch(SnowplowMalfunctioningException $ex) {
        try {
          $this->municipalServices->sendSnowplow();
        } catch(SnowplowMalfunctioningException $ex) {

        }
      }

    }
}
