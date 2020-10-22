<?php


namespace App\Event;


use App\Entity\UserPreference;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $defaultLocale;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    /**
     * UserSubscriber constructor.
     * @param Mailer $mailer
     * @param EntityManagerInterface $entityManager
     * @param string $defaultLocale
     */
    public function __construct(Mailer $mailer, EntityManagerInterface $entityManager, $defaultLocale='en')
    {

        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        //add locale user preference
        $preferences = new UserPreference();
        $preferences->setLocale($this->defaultLocale);

        $user = $event->getRegisteredUser();
        $user->setPreference($preferences);

        // required, if `cascade: persist` is not set -> preference in the user entity
        $this->entityManager->persist($preferences);


        $this->entityManager->flush($user);

        //create Method in Mailer, the Logic for sending Mail
        // in a method sendConfirmationEMail
        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }
}