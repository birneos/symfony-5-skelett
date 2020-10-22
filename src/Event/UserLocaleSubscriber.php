<?php


namespace App\Event;


use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * @description When User registered then set Locale to Session
 * Class UserLocaleSubscriber
 * @package App\Event
 */
class UserLocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * UserLocaleSubscriber constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {

        //15 because LocaleSubscriber has 20 and 15 means it processed before
       return [
           SecurityEvents::INTERACTIVE_LOGIN => [
                'onInteractiveLogin',
               15
           ]
       ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event){

        /**
         * @var User $user
         */
       $user = $event->getAuthenticationToken()->getUser();

       $this->session->set('_locale', $user->getPreference()->getLocale());


    }

}