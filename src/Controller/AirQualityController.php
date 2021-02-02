<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class AirQualityController
 * @package App\Controller
 */
class AirQualityController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/", name="air_quality.index")
     *
     * @return Response
     */
    public function index(): Response
    {
        $response = $this->httpClient->request(
        'GET',
        'http://api.gios.gov.pl/pjp-api/rest/station/findAll'
        );

        $stations = json_decode($response->getContent(), true);

        return $this->render('air_quality/index.html.twig', [
            'stations' => $stations,
        ]);
    }

    /**
     * @Route("/station", name="air_quality.station_sensors")
     * @param Request $request
     *
     * @return Response
     */
    public function showStationSensors(Request $request): Response
    {
        $idStation = $request->get('idStation');

        $response = $this->httpClient->request(
            'GET',
            "http://api.gios.gov.pl/pjp-api/rest/station/sensors/$idStation"
        );
        $sensors = json_decode($response->getContent(), true);

        $response = $this->httpClient->request(
            'GET',
            "http://api.gios.gov.pl/pjp-api/rest/aqindex/getIndex/$idStation"
        );

        $airQuality = json_decode($response->getContent(), true);

        $sensorValuesNames = [];
        foreach ($sensors as $sensor) {
            $sensorName = $sensor['param']['paramName'].' ('.$sensor['param']['paramCode'].')';
            $indexLevel = strtolower($sensor['param']['paramFormula']).'IndexLevel';
            if (array_key_exists($indexLevel, $airQuality)) {
                $sensorValuesNames[$sensorName] = $airQuality[$indexLevel]['indexLevelName'];
            }
        }

        $airQualitySt = '';
        switch ($airQuality['stIndexLevel']['id']) {
            case 0:
                $airQualitySt = 'success';
                break;
            case 1:
                $airQualitySt = 'info';
                break;
            case 2:
                $airQualitySt = 'primary';
                break;
            case 3:
                $airQualitySt = 'warning';
                break;
            case 4:
                $airQualitySt = 'danger';
                break;
        }

        return $this->render('air_quality/station_sensors.html.twig', [
            'sensors' => $sensors,
            'airQuality' => $airQuality,
            'airQualitySt' => $airQualitySt,
            'sensorValuesNames' => $sensorValuesNames,
        ]);

    }
}
