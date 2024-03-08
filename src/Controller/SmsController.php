<?php

namespace App\Controller;

use App\Service\SmsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{
    #[Route('/sms', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('sms/index.html.twig',['smsSent'=>false]);
    }

    #[Route('/sendSms', name: 'send_sms', methods:['GET', 'POST'])]
    public function sendSms(Request $request, SmsGenerator $smsGenerator): Response
    {
        $number = $request->request->get('number');
        $name = $request->request->get('name');
        $text = $request->request->get('text');
        $number_test = $_ENV['twilio_to_number'];

        // Check if $name is not null before passing it to sendSms
        if ($name !== null) {
            $smsGenerator->sendSms($number_test, $name, $text);
        } else {
            // Handle the case where $name is null (perhaps set a default value or show an error)
        }

        return $this->render('sms/index.html.twig', ['smsSent' => true]);
    }
}