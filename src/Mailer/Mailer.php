<?php

namespace App\Mailer;


use App\Entity\User;
use Twig\Environment;

class Mailer{
    /**
     * @var string
     */
    private $mailFrom;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body =$this->twig->render('email/registration.html.twig',[
            'user' => $user
        ]);
        $message = (new \Swift_Message())
            ->setSubject('Welcome to the Micro-Post app!')
            ->setFrom($this->mailFrom)
            ->setTo($user->getEmail())
            ->setBody($body,'text/html');

        $this->mailer->send($message);
    }
}