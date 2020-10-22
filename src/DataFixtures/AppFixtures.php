<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'Marco Linke',
            'password' => 'secret123#',
            'roles' => [User::ROLE_ADMIN, User::ROLE_USER]
        ],
        [
            'username' => 'john_doe',
            'email' => 'john_doe@blog.com',
            'name' => 'John Doe',
            'password' => 'secret123#',
              'roles' => User::ROLE_USER
        ],
        [
            'username' => 'robbie_williams',
            'email' => 'robbie_williams@blog.com',
            'name' => 'Robbie Williams',
            'password' => 'secret123#',
            'roles' => User::ROLE_USER
        ],
        [
            'username' => 'rocky_balboa',
            'email' => 'rocky_balboa@blog.com',
            'name' => 'Rocky Balboa',
            'password' => 'secret123#',
            'roles' => User::ROLE_USER
        ],

    ];

    private const LANGUAGES = ['en','fr'];


    /**
     * @var \Faker\Generator
     */
    private $faker;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create(); //first step
    }


    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->loadUsers($manager);
        $this->loadMicroPost($manager);
    }


    private function loadMicroPost(ObjectManager $manager)
    {
        for($i=0; $i <10; $i++){
            $micropost = new MicroPost();
            $micropost->setText($this->faker->realText());
            $micropost->setTime($this->faker->dateTimeThisCentury);


            $authorReference = $this->getRandomUserReference();
            $micropost->setUser($authorReference);

           # $micropost->setUser($this->getReference('JohnDoe'));
            $manager->persist($micropost);
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach(self::USERS as $userFixture){
            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setFullname($userFixture['name']);

            $user->setEmail($userFixture['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,$userFixture['password']));
            $user->setRoles((array)$userFixture['roles']);
            $user->setEnabled(true);

            $this->addReference('user_' . $userFixture['username'], $user);

            $preferences = new UserPreference();
            $preferences->setLocale(self::LANGUAGES[rand(0,1)]);

            $user->setPreference($preferences);
            $manager->persist($preferences);
            $manager->persist($user);
        }
        $manager->flush();
    }


    /**
     * @return User|null
     */
    public function getRandomUserReference():User
    {

        return $this->getReference( 'user_' . self::USERS[rand(0, 2)]['username']);

    }
}
