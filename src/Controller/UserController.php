<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RoleType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;


class UserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var RouterInterface
     */
    private $router;


    /**
     * UserController constructor.
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        FlashBagInterface $flashBag
    )
    {

        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/profile/{id}", name="user_profile")
     * @Security ("is_granted('ROLE_USER')")
     * @param User $user
     * @param Request $request
     * @param TokenStorageInterface $tokenStorage
     * @param UserRepository $userRepository
     * @return Response
     */
    public function edit(User $user, Request $request, TokenStorageInterface $tokenStorage, UserRepository $userRepository)
    {
//        if (null === $user) {
//            $current =  $tokenStorage->getToken()->getUser();
//        }


        if (null !== $user) {
            #  $current =  $tokenStorage->getToken()->getUser();
            #  $current =  $userRepository->findOneBySomeField( $request->get('id'));
            $current = $user;
            $roles = [];

            foreach ($current->getRoles() as $role => $v) {
                $roles[$v] = $role;
            }


            $defaultData = [

                'fullname' => $current->getFullname(),
                'email' => $current->getEmail(),
//            'roles' => [$current->getRoles()]
            ];


            $form = $this->createFormBuilder($defaultData)
                ->add('fullname', TextType::class, [
                    'constraints' => [new NotBlank(), new Length([
                        'min' => 8,
                        'max' => 50,
                        'minMessage' => 'mindestens 30 Zeichen',
                        'maxMessage' => 'mindestens 50 Zeichen'
                    ])],
                    'label' => 'Benutzername'
                ])
                ->add('email', EmailType::class, [
                    'constraints' => new Email(array('message' => 'Falsches E-Mail Format')),
                    'label' => 'E-Mail'
                ])
                ->add('roles', ChoiceType::class,
                    [
                        'choices' => $roles,
                        'expanded' => false,
                        'multiple' => true,
                        'disabled' => true
                    ])
                ->add('Speichern', SubmitType::class)
                ->getForm();


            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->entityManager->persist(
                    $user->setFullname($form->getData()['fullname']),
                    $user->setEmail($form->getData()['email'])
                );
                $this->entityManager->flush();
                $this->flashBag->add('notice', 'Profil wurde geupdatet');
                return new RedirectResponse(
                    $this->router->generate('user_profile', ['id' => $user->getId()])
                );
            }
            # dump($request->attributes->get('_route_params'));

        }

        return $this->render('user/index.html.twig', [
            'user ' => $current,
            'form' => $form->createView() ?? []

        ]);
    }
}
