<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AirQualityController extends AbstractController
{
    /**
     * @Route("/", name="air_quality")
     */
    public function index(): Response
    {
        return $this->render('air_quality/index.html.twig', [
            'controller_name' => 'AirQualityController',
        ]);
    }
}
