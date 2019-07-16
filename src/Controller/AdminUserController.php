<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        return $this->render('admin_user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new")
     */
    public function new(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
            $user = new User();
            $user->setFirstName($userData['firstname']);
            $user->setMiddleName($userData['middlename']);
            $user->setLastName($userData['lastname']);
            $user->setBirthDate($userData['birthdate']);
            $user->setEmail($userData['email']);
            $user->setGender($userData['gender']);
            $user->setPhone($userData['phone']);
            $user->setPassword($encoder->encodePassword(
                $user,
                $userData['firstname']
            )); // plain password is firstname. Security issue
            $user->setUserStatus(0);
            $user->setApiToken($userData['email']); // this attribute should be deleted from entity cause api object used instead

            $apiToken = new ApiToken($user);

            $em->persist($user);
            $em->persist($apiToken);
            $em->flush();


            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin_user_new/index.html.twig', [
//            'controller_name' => 'UserController',
            'userForm' => $form->createView(),
        ]);
    }
}
