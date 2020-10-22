<?php

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class MailerTest extends TestCase
{

    public function testConfirmationEmail()
    {
        $user = new User();
        $user->setEmail('john@doe.com');

        $mailerMock = $this
            ->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twigMock =$this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();


        $mailerMock->expects($this->once())->method('send')->with($this->callback(function($subject){
            $messageStr = (string) $subject;
            dump($messageStr);

            return (strpos($messageStr, "From: test@doe.com") !== false)
                && (strpos($messageStr, "To: john@doe.com") !== false)
                && (strpos($messageStr, "Content-Type: text/html") !== false);
           # return true;
        }));

        //call method one times
        $twigMock->expects($this->once())
            ->method('render')
            ->with('email/registration.html.twig',[
                'user'=> $user
            ]);
      //  ->willReturn("ja juhu"); return something else in messageStr if you want for testing

        $mailer = new Mailer($mailerMock, $twigMock,'test@doe.com');
        $mailer->sendConfirmationEmail($user);


    }
}
