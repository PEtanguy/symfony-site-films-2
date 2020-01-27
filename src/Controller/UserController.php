<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\RegisterUserType;
use App\Entity\User;


class UserController extends AbstractController
{
  /**
   * @Route("/user_management", name="user_management")
   */
  public function index(Request $request)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $users = $entityManager->getRepository(User::class)->findAll();
    return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
  }


    /**
     * @Route("/signup", name="user/signup")
     */
    public function signup(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();
        dump($users);

        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setUsername($form->get('username')->getData());
            $user->setFullName($form->get('fullName')->getData());
            $user->setAdmin(true);
            $user->setJoinDate(new \DateTime('now'));
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form->get('password')->getData())
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('user/signup.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
      $error = $authenticationUtils->getLastAuthenticationError();
      $lastUsername = $authenticationUtils->getLastUsername();


      return $this->render('security/login.html.twig', [
            'last_username' => '$lastUsername',
            'error' => $error,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit")
     */
    public function edit(User $user,Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();
            $this->addFlash('success', 'User Updated! Inaccuracies squashed!');
            return $this->redirectToRoute('home');
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id}/delete", name="user_delete")
     */
    public function delete(User $user,Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('user_management');
    }
}
