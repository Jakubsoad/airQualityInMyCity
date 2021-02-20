<?php

namespace App\Controller\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class AirQualityService
 * @package App\Controller\Service
 */
class AirQualityService
{
    /** @var HttpClientInterface */
    private $httpClient;

    /**
     * AirQualityController constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAllStations(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'http://api.gios.gov.pl/pjp-api/rest/station/findAll'
        );

        return json_decode($response->getContent(), true);
    }

    /**
     * @param int $idStation
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getStationSensors(int $idStation): array
    {
        $response = $this->httpClient->request(
            'GET',
            "http://api.gios.gov.pl/pjp-api/rest/station/sensors/$idStation"
        );

        return json_decode($response->getContent(), true);
    }

    /**
     * @param int $idStation
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAirQualityForStation(int $idStation): array
    {
        $response = $this->httpClient->request(
        'GET',
        "http://api.gios.gov.pl/pjp-api/rest/aqindex/getIndex/$idStation"
        );

        return json_decode($response->getContent(), true);
    }

    /**
     * @param array $sensors
     * @param array $airQuality
     * @return array
     */
    public function matchSensorsWithData(array $sensors, array $airQuality): array
    {
        $response = [];
        foreach ($sensors as $sensor) {
            $sensorName = $sensor['param']['paramName'].' ('.$sensor['param']['paramCode'].')';
            $indexLevel = strtolower($sensor['param']['paramFormula']).'IndexLevel';
            if (array_key_exists($indexLevel, $airQuality)) {
                $sensorValuesNames[$sensorName] = $airQuality[$indexLevel]['indexLevelName'];
            }
        }

        if ([] === $response) {
            $response = ['Brak danych dla twojej stacji'];
        }

        return $response;
    }

    /**
     * @param string $stIndexLevel
     * @return string
     */
    public function getBootstrapClassByStIndex(string $stIndexLevel): string
    {
        $response = '';
        switch ($stIndexLevel) {
            case 0:
                $response = 'success';
                break;
            case 1:
                $response = 'info';
                break;
            case 2:
                $response = 'primary';
                break;
            case 3:
                $response = 'warning';
                break;
            case 4:
                $response = 'danger';
                break;
        }

        return $response;
    }
}
