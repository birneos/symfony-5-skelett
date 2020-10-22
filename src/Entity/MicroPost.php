<?php

namespace App\Entity;

use App\Repository\MicroPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Twig\Environment;

/**
 * @ORM\Entity(repositoryClass=MicroPostRepository::class)
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * MicroPost constructor.
     */
    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column (type="string", length=254)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="Bitte mehr als 3 Buchstaben eingeben")
     */
    private $text;

    /**
     * @ORM\Column (type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;


    /**
     * OWNING SIDE
     * @ORM\ManyToMany (targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable ( name="post_likes",
     *     joinColumns={@ORM\JoinColumn(name="post_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *     )
     */
    private $likedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * description: automatically pre set the time, when persisting something
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist()
    {
        $this->time = new \DateTime();
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getLikedBy():Collection
    {
        return $this->likedBy;
    }

    public function like(User $user)
    {
        /* check whether collection contains user*/
       if( $this->likedBy->contains($user))
       {
           return;
       }

       /* add user if not contains*/
       $this->likedBy->add($user);
    }

}
