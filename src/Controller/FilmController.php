<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Form\MovieFormType;
use App\Entity\User;
use App\Entity\Movie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class FilmController extends AbstractController
{
    /**
     * @Route("/film/new", name="film/new")
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request, TokenStorageInterface $tokenStorage)
    {
        // createFormBuilder is a shortcut to get the "form factory"
        // and then call "createBuilder()" on it

        $entityManager = $this->getDoctrine()->getManager();

        // $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();
        dump($users);
        $user = $this->getUser();
        dump($user);

        $movie = new Movie();
        $user = $this->getUser();
        $movie->setUser($user);
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);
        $temp_rating = $form->get('rating')->getData();

        if ($temp_rating != null || $temp_rating != 0)
        {
          $movie->setNumberOfVoters(1);
          $movie->setRating($temp_rating);
          $array = $movie->getVotesValue();
          array_push($array, $temp_rating);
        }

        if ($form->isSubmitted() && $form->isValid())
        {
          $entityManager->persist($movie);
          $entityManager->flush();
          return $this->redirectToRoute('home');
        }

        return $this->render('film/new.html.twig', [
            'controller_name' => 'FilmController',
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/film/{id}/rate", name="film_rate")
     */
    public function rate(Movie $movie,Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);


        $temp_rating = $form->get('rating')->getData();
        $int = (int)$temp_rating;
        $existing_rating = $movie->getRating();
        $numberOfVoters = $movie->getNumberOfVoters();

        if ($numberOfVoters == null || $numberOfVoters == 0) {
          $numberOfVoters = 1;
          $rate = $temp_rating ;
          $movie->setRating($rate);
          $movie->setNumberOfVoters($numberOfVoters);
          dump($numberOfVoters, $rate, $array);
        }
        else {
          $numberOfVoters = $movie->getNumberOfVoters();
          $numberOfVoters = $numberOfVoters + 1;
          $rate = $temp_rating ;
          $movie->setRating($rate);
          $movie->setNumberOfVoters($numberOfVoters);

          $array = $movie->getVotesValue();
          array_push($array, $temp_rating);
          $movie->setVotesValue($array);
          dump($numberOfVoters, $rate, $array);

        }

        // $movie->setRating($rate);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($movie);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('film/rate.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/film/{id}/edit", name="film_edit")
     */
    public function edit(Movie $movie,Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);


        $temp_rating = $form->get('rating')->getData();
        $int = (int)$temp_rating;
        $existing_rating = $movie->getRating();
        $numberOfVoters = $movie->getNumberOfVoters();
        if ($numberOfVoters == null || $numberOfVoters == 0) {
          $numberOfVoters = 1;
          $rate = $temp_rating ;
          $movie->setRating($rate);
          $movie->setNumberOfVoters($numberOfVoters);
          dump($numberOfVoters, $rate, $array);
        }
        else {
          $numberOfVoters = $movie->getNumberOfVoters();
          $numberOfVoters = $numberOfVoters + 1;
          $rate = $temp_rating ;
          $movie->setRating($rate);
          $movie->setNumberOfVoters($numberOfVoters);

          $array = $movie->getVotesValue();
          array_push($array, $temp_rating);
          $movie->setVotesValue($array);
          dump($numberOfVoters, $rate, $array);

        }




            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($movie);
                $entityManager->flush();
                // return $this->redirectToRoute('home');
            }
            // return $this->redirectToRoute('home');
        return $this->render('film/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/film/{id}/delete", name="film_delete")
     */
    public function delete(Movie $movie,Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($movie);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $movies = $entityManager->getRepository(Movie::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();
        $total = 0;
        $votes = $movies[0]->getVotesValue();



        dump(count($votes), $votes);

        return $this->render('film/index.html.twig', [
            'controller_name' => 'FilmController',
            'users' => $users,
            'movies' => $movies,
            'total' => $total,

        ]);
    }
}
