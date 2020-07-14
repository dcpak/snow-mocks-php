<?php

use PHPUnit\Framework\TestCase;

require __DIR__.'/../vendor/autoload.php';

class SnowRescueServiceTest extends TestCase {
  private $weatherForecastService;
  private $municipalServices;
  private $pressService;

  protected function setUp(): void {
    parent::setUp();
    $this->weatherForecastService = $this->createStub(WeatherForecastService::class);
    $this->municipalServices = $this->createMock(MunicipalServices::class);
    $this->pressService = $this->createMock(PressService::class);

  }

  public function testSendingSander() {
    $service = new SnowRescueService($this->weatherForecastService, $this->municipalServices, $this->pressService);

    $this->weatherForecastService->method('getAverageTemperatureInCelsius')->willReturn(-1);

    $this->municipalServices->expects($this->once())->method('sendSander');

    $service->checkForecastAndRescue();
  }

  public function testNoSendingSander() {
    $service = new SnowRescueService($this->weatherForecastService, $this->municipalServices, $this->pressService);

    $this->weatherForecastService->method('getAverageTemperatureInCelsius')->willReturn(3);

    $this->municipalServices->expects($this->never())->method('sendSander');

    $service->checkForecastAndRescue();
  }

  /**
   * @dataProvider providerSendingMachine
   */
  function testSendigMachine($checkType, $checkValue, $number, $machineAction) {
    $service = new SnowRescueService($this->weatherForecastService, $this->municipalServices, $this->pressService);
    if($checkType == 'temp')
      $this->weatherForecastService->method('getAverageTemperatureInCelsius')->willReturn($checkValue);
    elseif($checkType == 'snow')
      $this->weatherForecastService->method('getSnowFallHeightInMM')->willReturn($checkValue);
    $this->municipalServices->expects($number)->method($machineAction);
    $service->checkForecastAndRescue();
  }

  function providerSendingMachine() {
    return array(
      'send_sander_for_temperature_below_0' => array('temp', -1, $this->once(), 'sendSander'),
      'not_send_sander_for_temperature_higer_or_equal_0' => array('temp', 3, $this->never(), 'sendSander'),
      'send_snowplow_for_snow_up_4mm' => array('snow', 4, $this->once(), 'sendSnowplow'),
      'not_send_snowplow_for_snow_up_1mm' => array('snow', 1, $this->never(), 'sendSnowplow'),
    );
  }

  /*
  public function testSnowplowMalfunction() {
    $service = new SnowRescueService($this->weatherForecastService, $this->municipalServices, $this->pressService);

    $this->weatherForecastService->method('getSnowFallHeightInMM')->willReturn(4);

    $this->municipalServices->method('sendSnowplow')->will($this->throwException(SnowplowMalfunctioningException::class));

    $this->municipalServices->expects($this->exactly(2))->method('sendSnowplow');

    $service->checkForecastAndRescue();
  }
  */

  public function testTwoSnowplowsWhenSnowHigherThan5() {
    $service = new SnowRescueService($this->weatherForecastService, $this->municipalServices, $this->pressService);

    $this->weatherForecastService->method('getSnowFallHeightInMM')->willReturn(6);

    $this->municipalServices->expects($this->exactly(2))->method('sendSnowplow');

    $service->checkForecastAndRescue();
  }


  public function testThreeSnowplowsOneSenderWhenSnowHigherThan10AndTempBelowMinus10() {
    $service = new SnowRescueService($this->weatherForecastService, $this->municipalServices, $this->pressService);
    $this->weatherForecastService->method('getAverageTemperatureInCelsius')->willReturn(11);
    $this->weatherForecastService->method('getSnowFallHeightInMM')->willReturn(11);

    $this->municipalServices->expects($this->exactly(3))->method('sendSnowplow');
    $this->municipalServices->expects($this->exactly(1))->method('sendSender');
    $service->checkForecastAndRescue();
  }

}
