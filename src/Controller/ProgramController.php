<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;


/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     * 
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]);
    }

    /**
     * The controller for the program add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */
    public function show(Program $program): Response
    {
        $seasons = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findBy(['program' => $program], ['number' => 'ASC']);
    
        if (!$seasons) {
            throw $this->createNotFoundException(
                'Not season found'
            );
        }
    
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
    * getting a program by seasonId
    *
    * @Route("/{programId}/season/{seasonId}", name="season_show")
    * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
    * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
    * @return Response
    */
    public function showSeason(Program $program, Season $season): Response
    {
    return $this->render('program/season_show.html.twig', [
        'program' => $program,
        'season' => $season,
    ]);
    }

    /**
    * getting a program by seasonId
    *
    * @Route("/{programId}/season/{seasonId}/episode/{episodeId}", name="episode_show")
    * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
    * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
    * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
    * @return Response
    */
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}