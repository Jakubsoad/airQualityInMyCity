<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AirQualityController
 * @package App\Controller
 */
class AirQualityController extends AbstractController
{
    /**
     * @Route("/chooseCity", name="air_quality")
     */
    public function index(): Response
    {
        return $this->render('air_quality/index.html.twig');
    }
}
