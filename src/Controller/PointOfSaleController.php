<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PointOfSaleController extends AbstractController
{
    #[Route('/sale', name: 'app_point_of_sale')]
    public function index(): Response
    {
        return $this->render('point_of_sale/index.html.twig', [
            'controller_name' => 'PointOfSaleController',
        ]);
    }
}
