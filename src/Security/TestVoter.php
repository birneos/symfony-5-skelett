<?php


namespace App\Security;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TestVoter extends Voter
{

    protected function supports(string $attribute, $subject)
    {
        dump("ich war hier");
        // TODO: Implement supports() method.
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {

        // TODO: Implement voteOnAttribute() method.
    }
}