<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{

    private $entityManager;
    private $mail;

    public function __construct(EntityManagerInterface $entityManager,  Mail $mail){
        $this->entityManager = $entityManager;
        $this->mail = $mail;
    }


    


    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        // Vérifie si la commande existe et appartient à l'utilisateur connecté
        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Vérifie si la commande n'est pas déjà marquée comme payée
        if (!$order->isIsPaid()) {
            // Logique pour marquer la commande comme payée
            $cart->remove(); // Vide le panier
            $order->setIsPaid(true); // Marque la commande comme payée
            $this->entityManager->flush(); // Sauvegarde les changements dans la base de données

           
            $content = "Bonjour " . $order->getUser()->getFirstName() . "<br>" .
            "Merci pour votre commande sur notre site";
 
            $this->mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstName(),"Commande validée", $content);
        }

        // Affiche la page de succès
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }



}
