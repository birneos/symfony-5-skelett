<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields="username", message="This username is already used.")
 * @UniqueEntity(fields="email", message="This e-mail is already used.")
 */
class User implements UserInterface, \Serializable
{

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';
    /**
     * @ORM\Column(type="string", length=150, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=50)
     */
    private $fullname;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=254)
     */
    private $password;
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=8,max=4096)
     */
    private $plainPassword;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user", cascade={"persist"})
     */
    private $posts;
    /**
     * @var array
     * @ORM\Column (type="simple_array")
     */
    private $roles;
    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=50)
     */
    private $username;

    /**
     * Bidirectional (INVERSE SIDE)
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
     */
    private $follower;


    /**
     * Bidirectional (OWNING SIDE)
     * @ORM\ManyToMany (targetEntity="App\Entity\User", inversedBy="follower")
     * @ORM\JoinTable(name="following",
     *     joinColumns={ @ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={ @ORM\JoinColumn(name="following_user_id", referencedColumnName="id")}
     *     )
     *
     */
    private $following;

    /**
     * INVERSE SIDE
     * @ORM\ManyToMany (targetEntity="App\Entity\MicroPost", mappedBy="likedBy")
     */
    private $postsLiked;

    /**
     * @ORM\Column (type="string", nullable=true, length=30)
     */
    private $confirmationToken;

    /**
     * @ORM\Column (type="boolean")
     */
    private $enabled;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserPreference" )
     */
    private $preference;

    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param mixed $confirmationToken
     */
    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
       return null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function unserialize($serialized)
    {
        list($this->id,$this->username,$this->password) = unserialize($serialized);
    }

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->follower = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->postsLiked = new ArrayCollection();

        $this->roles = [self::ROLE_USER];
        $this->enabled = false;
    }

    /**
     * @return Collection
     */
    public function getFollower(): Collection
    {
        return $this->follower;
    }

    /**
     * @return Collection
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function follow(User $user){

        if($this->getFollowing()->contains($user)){
            return;
        }

        $this->getFollowing()->add($user);
    }

    /**
     * @return Collection
     */
    public function getPostsLiked(): Collection
    {
        return $this->postsLiked;
    }

    /**
     * @return false
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param false $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return UserPreference|null
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * @param mixed $preference
     */
    public function setPreference($preference): void
    {
        $this->preference = $preference;
    }


}
