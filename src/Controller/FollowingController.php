<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\DBAL\Logging\SQLLogger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FollowingController
 * @Route ("/")
 */
class FollowingController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * FollowingController constructor.
     * @param LoggerInterface $SqlLogger
     */
    public function __construct(LoggerInterface $SqlLogger)
    {

        $this->logger = $SqlLogger;
    }

    /**
     * @Route("/follow/{id}", name="following_follow")
     * @param User $followUser
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function follow(User $followUser)
    {

        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();

        if($currentUser->getId() !== $followUser->getId()){

            $currentUser->follow($followUser);

            $this->getDoctrine()->getManager()->flush();
        }


        return $this->redirectToRoute('micro_post_user', ['username' => $this->getUser()->getUsername()]);

    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     * @param User $unfollowUser
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unfollow(User $unfollowUser)
    {

        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();
        $currentUser->getFollowing()->removeElement($unfollowUser);

        $this->getDoctrine()->getManager()->flush();


        $this->logger->info("Unfollow ...");

      #  dump($stack);die;

        return $this->redirectToRoute('micro_post_user', [
            'username' => $this->getUser()->getUsername()]);

    }

}