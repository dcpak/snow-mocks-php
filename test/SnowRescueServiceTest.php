<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

class SnowRescueServiceTest extends TestCase
{
    private $weatherForecastService;
    private $municipalServices;
    private $pressService;

    protected function setUp() : void
    {
        parent::setUp();
        $this->weatherForecastService = $this->createStub(WeatherForecastService::class);
        $this->municipalServices = $this->createMock(MunicipalServices::class);
        $this->pressService = $this->createMock(PressService::class);

    }

    public function testSendingSander()
    {
        $service = new SnowRescueService($this->weatherForecastService, $this->municipalServices, $this->pressService);

        $this->weatherForecastService->method('getAverageTemperatureInCelsius')->willReturn(-1);

        $this->municipalServices->expects($this->once())->method('sendSander');

        $service->checkForecastAndRescue();
    }
}
