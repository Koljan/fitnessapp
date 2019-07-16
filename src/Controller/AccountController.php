<?php

namespace App\Controller;

use App\Form\PasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController
 *
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $form = $this->createForm(PasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user = $this->getUser();
            $user->setPassword($encoder->encodePassword(
                $user,
                $formData['password']
            ));
            $user->setUserStatus(1);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Password OK');
        }

        return $this->render('account/index.html.twig', [
            'passwordForm' => $form->createView(),
            ]);
    }

    /**
     * @Route("/api/account", name="app_api_account")
     */
    public function accountApi()
    {
        // ApiTokenAuth OK

        // redirect to app_account , where user has to set password
        return $this->redirectToRoute('app_account');

        // - set status to active
        // - delete token from database OPTIONAL for scope of test this task

//        dd($user);
        //
//        return $this->json($user, 200, [], [
//            'groups' => ['main']
//        ]);
    }
}
