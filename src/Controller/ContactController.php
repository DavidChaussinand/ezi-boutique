<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
        
    }


    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            
            // Construisez le contenu de l'email
            $content = "Vous avez reçu un message de : " . $contactFormData['nom'] . ' ' . $contactFormData['prenom'] . "\n";
            $content .= "Email : " . $contactFormData['email'] . "\n";
            $content .= "Message :\n" . $contactFormData['content'];
            
            
            
            // Utilisez la classe Mail pour envoyer l'email
          
            $this->mail->send('lebib07@live.fr',"de la part d'un visiteur","Demande par le formulaire de contact",$content);       
            
            $this->addFlash('notice', 'Votre message a bien été envoyé, nous vous recontacterons dans les plus brefs délais.');

            return $this->redirectToRoute('app_contact');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
