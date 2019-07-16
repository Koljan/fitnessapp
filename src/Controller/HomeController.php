<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(UserRepository $userRepository, CourseRepository $courseRepository)
    {
        $allUsers = $userRepository->findAll();
        $allCourses = $courseRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $allUsers,
            'courses' => $allCourses,
        ]);
    }
}
