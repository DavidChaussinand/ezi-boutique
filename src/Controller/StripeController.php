<?php
        namespace App\Controller;
        
        use App\Classe\Cart;
        use App\Entity\Order;
        use App\Entity\Produit;
        use Doctrine\ORM\EntityManagerInterface;
        use Stripe\Checkout\Session;
        use Stripe\Exception\ApiErrorException;
        use Stripe\Stripe;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Response;
        use Symfony\Component\Routing\Annotation\Route;
        
        class StripeController extends AbstractController
        {
            #[Route('/commande/create-session/{reference}', name: 'app_stripe_create_session')]
            public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
            {
                try {
                    $products_for_stripe = [];
                    $YOUR_DOMAIN = 'http://127.0.0.1:8000';
                    
                    $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
                    if (!$order) {
                        throw new \Exception("Order not found");
                    }
        
                    foreach ($order->getOrderDetails()->getValues() as $product) {
                        $product_object = $entityManager->getRepository(Produit::class)->findOneBy(['name' => $product->getProduct()]);
                        if (!$product_object) {
                            throw new \Exception("Product not found");
                        }
                        $products_for_stripe[] = [
                            'price_data' => [
                                'currency' => 'eur',
                                'unit_amount' => $product->getPrice(), // Convert to cents
                                'product_data' => [
                                    'name' => $product->getProduct(),
                                    'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                                ],
                            ],
                            'quantity' => $product->getQuantity(),
                        ];
                    }

                    $products_for_stripe[] = [
                        'price_data' => [
                            'currency' => 'eur',
                            'unit_amount' => $order->getCarrierPrice() * 100 , // Converti en centimes
                            'product_data' => [
                                'name' => 'Frais de livraison',
                                // 'images' => ['url_de_l_image_pour_la_livraison'], // Optionnel
                            ],
                        ],
                        'quantity' => 1,
                    ];
        
                    $stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'];
                    Stripe::setApiKey($stripeSecretKey);
                    
        
                    $checkout_session = Session::create([
                        'customer_email' => $this->getUser()->getEmail(),
                        'line_items' => [$products_for_stripe],
                        'mode' => 'payment',
                        'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                        'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
                    ]);
        
                    $order->setStripeSessionId($checkout_session->id);
                    $entityManager->flush();
        
                    return new JsonResponse(['id' => $checkout_session->id]);
                } catch (ApiErrorException $e) {
                    // Handle Stripe API error
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
                } catch (\Exception $e) {
                    // Handle general errors
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }







