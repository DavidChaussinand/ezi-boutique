<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    #[Route('/produits', name: 'app_products')]
    public function index(): Response
    {   
        
        $products = $this->entityManager->getRepository(Produit::class)->findAll();



        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products'=> $products,
        ]);
    }


    #[Route('/produit/{slug}', name: 'app_product')]
    public function show($slug): Response
    {   
        
        $product = $this->entityManager->getRepository(Produit::class)->findOneBySlug($slug);
        $comments= $product->getComments();

        if(!$product){
            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProductController',
            'product'=> $product,
            'comments'=> $comments,
        ]);
    }
}
