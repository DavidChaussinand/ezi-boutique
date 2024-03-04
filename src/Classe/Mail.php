<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Mail
{
    private $api_key;
    private $api_key_secret;

    public function __construct(ParameterBagInterface $params)
    {
        $this->api_key = $params->get('mailjet_api_key');
        $this->api_key_secret = $params->get('mailjet_api_secret');
    }



    public function send($to_email, $to_name , $subject, $content){
        
        

        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "lebib07@live.fr",
                        'Name' => "Le gérant d'EZI"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4318602,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                      
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }


}