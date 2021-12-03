<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();
        return $this->render('/program/index.html.twig', ['programs' => $programs]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */

    public function show(Program $program): Response
    {
        $seasons = $this->getDoctrine()->getRepository(Season::class)->findBy(['program' => $program], ['number' => 'ASC']);
        if (!$seasons) {
            throw $this->createNotFoundException(
                'No program found . '
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * Getting a program by seasonid
     *
     * @Route("/{programId}/seasons/{seasonId} ", name="season_show")
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
     * Getting a program by seasonid
     * 
     * @Route("/program/{programId}/season/{seasonId}/episode/{episodeId}", name="episode_show")
     * 
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [

            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
