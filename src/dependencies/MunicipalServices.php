<?php

interface MunicipalServices
{
    /**
     * Send snowplow.
     * @throws SnowplowMalfunctioningException if operation fail
     */
    public function sendSnowplow(): void;

    public function sendSander(): void;
}

