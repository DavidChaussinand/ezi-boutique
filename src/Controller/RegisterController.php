<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController

{
    private $entityManager;
    private $mail;

    public function __construct(EntityManagerInterface $entityManager, Mail $mail){
        $this->entityManager = $entityManager;
        $this->mail = $mail;
    }
  


    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request , UserPasswordHasherInterface $passwordHasher): Response
    {   

        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user); 


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user= $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email){
                
                $plaintextPassword = $user->getPassword();
                
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                
                $content = "Bonjour ".$user->getFirstName().", nous vous confirmation que l'inscription sur notre site est validée";
                $this->mail->send($user->getEmail(),$user->getFirstName(),"inscription validé", $content);


                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez vous connecter à votre compte";
            } else {
                $notification = "l'email que vous avez renseigné existe dejà";
            }



             
          
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
