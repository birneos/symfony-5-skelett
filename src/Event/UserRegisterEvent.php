<?php


namespace App\Event;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;


/**
 * @comment Im RegisterController wird der Event ausgelöst (dispatch)
 * Class UserRegisterEvent
 * @package App\Event
 */
class UserRegisterEvent extends Event
{

    const NAME = 'user.register';

    /**
     * @var User
     */
    private $registeredUser;

    public function __construct(User $registeredUser)
        {
            $this->registeredUser = $registeredUser;
        }

    /**
     * @return User
     */
    public function getRegisteredUser(): User
    {
        return $this->registeredUser;
    }


}