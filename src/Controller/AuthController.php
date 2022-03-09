<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class AuthController extends AbstractController

{
    #[Route('/auth', name: 'auth')]
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()->getEmail();
//            Check if User already exist
            if ($userRepository->count(['email' => $email]) > 0) {
                $form->addError(new FormError('User already exists'));
                return $this->renderForm('auth/register.html.twig', [
                    'form' => $form,
                ]);
            }

//          FORM IS VALID HERE
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            return $this->redirectToRoute('jobs');
        }

        return $this->renderForm('auth/register.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/login', name: 'login')]
    public function login(Request $request, UserRepository $userRepository): Response
    {

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            $password = $form->getData()['password'];
//            Checking if Email Already Exists
            $user = $userRepository->findOneBy(['email' => $email]);
            if ($user === null) {
                $form->addError(new FormError("User doesn't exist"));
                return $this->renderForm('auth/login.html.twig', [
                    'form' => $form,
                ]);
            }
//          User Exists Here =>  Checking if Passwords Match
            if ($user->getPassword() !== $password) {
                $form->addError(new FormError('Please Verify your password'));
                return $this->renderForm('auth/login.html.twig', [
                    'form' => $form,
                ]);
            }
//            User Logged In successfully => Saving User in session :)
            $this->get('session')->set('loggedinUser', $user);
            return $this->redirectToRoute('jobs');
        }

        return $this->renderForm('auth/login.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        $this->get('session')->set('loggedinUser', null);
        return $this->redirect('/');
    }

}
