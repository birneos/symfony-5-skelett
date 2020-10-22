<?php


namespace App\Controller;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/likes")
 *
 */
class LikesConroller extends AbstractController
{

    /**
     * @Route ("/likes/{id}", name="likes_like")
     * @param MicroPost $microPost
     */
    public function like(MicroPost $microPost)
    {
        $currentUser = $this->getUser();

        if(!$currentUser instanceof User){
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->like($currentUser);

        /**
         * @info if we make changes on entities we dont call persist
         */
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ]);
    }

    /**
     * @Route("unlike/{id}", name="likes_unlike")
     * @param MicroPost $microPost
     */
    public function unlike(MicroPost $microPost)
    {
        $currentUser = $this->getUser();

        if(!$currentUser instanceof User){
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->getLikedBy()->removeElement($currentUser);

        /**
         * @info if we make changes on entities we dont call persist
         */
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ]);
    }
}