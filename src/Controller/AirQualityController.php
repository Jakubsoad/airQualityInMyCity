<?php

namespace App\Controller;

use App\Controller\Service\AirQualityService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AirQualityController
 * @package App\Controller
 */
class AirQualityController extends AbstractController
{
    /** @var AirQualityService */
    private $airQualityService;

    /**
     * AirQualityController constructor.
     * @param AirQualityService $airQualityService
     */
    public function __construct(AirQualityService $airQualityService)
    {
        $this->airQualityService = $airQualityService;
    }

    /**
     * @Route("/", name="air_quality.index")
     *
     * @return Response
     * @throws Exception
     */
    public function index(): Response
    {
        try {
            $stations = $this->airQualityService->getAllStations();
        } catch (Exception $e) {
            $msg = 'Nie udało się pobrać stacji. Treść błędu: ';
            throw new Exception($msg.$e->getMessage());
        }

        return $this->render('air_quality/index.html.twig', [
            'stations' => $stations,
        ]);
    }

    /**
     * @Route("/station", name="air_quality.station_sensors")
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function showStationSensors(Request $request): Response
    {
        try {
            $sensors = $this->airQualityService->getStationSensors($request->get('idStation'));
        } catch (Exception $e) {
            $msg = 'Nie udało się pobrać czujników dla danej stacji. Treść błędu: ';
            throw new Exception($msg.$e->getMessage());
        }

        try {
            $airQuality = $this->airQualityService->getAirQualityForStation($request->get('idStation'));
        } catch (Exception $e) {
            $msg = 'Nie udało się pobrać wyników jakości powietrza dla danej stacji. Treść błędu: ';
            throw new Exception($msg.$e->getMessage());
        }

        $sensorValuesNames = $this->airQualityService->matchSensorsWithData($sensors, $airQuality);

        $airQualityBoostrapClass = $this->airQualityService->getBootstrapClassByStIndex($airQuality['stIndexLevel']['id']);

        return $this->render('air_quality/station_sensors.html.twig', [
            'sensors' => $sensors,
            'airQuality' => $airQuality,
            'airQualityBoostrapClass' => $airQualityBoostrapClass,
            'sensorValuesNames' => $sensorValuesNames,
        ]);
    }
}
