<?php

interface WeatherForecastService
{
    public function getSnowFallHeightInMM(): int;

    public function getAverageTemperatureInCelsius(): int;
}

