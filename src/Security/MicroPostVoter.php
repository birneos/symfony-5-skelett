<?php


namespace App\Security;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MicroPostVoter extends Voter
{

    const DELETE = 'delete';
    const EDIT = 'edit';
    const SHOW = 'show';
    /**
     * @var AccessDecisionManagerInterface
     */
    private $accessDecisionManager;

    /* FIRST CALL, IF TRUE THEN CALL voteONnAttribute */
    protected function supports(string $attribute, $subject)
    {

        if(!in_array($attribute,[self::SHOW,self::EDIT,self::DELETE]))
        {
            return false;
        }

        if(!$subject instanceof MicroPost)
            return false;

        return true;
    }



    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        // ROLE ADMIN , WE DECIDE WITH THE ACCESSDECIONMANAGER, HE GRANT HIM ALL THE POSSIBLE ACTIONS TO EDITION/DELETING POSTS

        if($this->accessDecisionManager->decide($token,[User::ROLE_ADMIN]))
        {
            return true;
        }

        $authUser = $token->getUser();

        if(!$authUser instanceof User)
        {
            return false;
        }

        /**
         * @var MicroPost $microPost
         */
        $microPost = $subject;

        return ($microPost->getUser()->getId() === $authUser->getId());
    }

    /**
     * MicroPostVoter constructor.
     * @param AccessDecisionManagerInterface $accessDecisionManager
     */
    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }
}