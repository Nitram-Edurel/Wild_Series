<?php

// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Actor;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Slugify;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * The controller for the program add form
     *
     * @Route("/new", name="new")
     */

    public function new(Request $request, MailerInterface $mailer, Slugify $slugify): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * Getting program by id
     *
     * @Route("/{slug}", name="show")
     * @return Response
     */

    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', ['program' => $program]);
    }

    /**
     * Getting a season for a program by id
     *
     * @Route("/{slug}/seasons/{season<^[0-9]+$>}", name="season_show")
     * @return Response
     */

    public function showSeason(
        Program $program,
        Season $season
    ): Response {

        return $this->render(
            'program/season_show.html.twig',
            [
                'program' => $program,
                'season' => $season
            ]
        );
    }

    /**
     * Getting an actor by id
     *
     * @Route("/actor/{actor<^[0-9]+$>}", name="actor_show")
     * @return Response
     */

    public function showActor(Actor $actor): Response
    {

        return $this->render(
            'program/actor_show.html.twig',
            [
                'actor' => $actor
            ]
        );
    }

    // /**
    // * Getting an episode for a season by id
    //  *
    //  * @Route("/{slug}/season/{season<^[0-9]+$>}/{slug}", name="episode_show")
    //  */


    // public function showEpisode(Program $program, Season $season, Episode $episode): Response
    //{

    //     return $this->render(
    //         'program/episode_show.html.twig',
    //         [
    //          'episode' => $episode,
    //             'program' => $program,
    //             'season' => $season,
    //         ]
    //     );
    // }

    /**
     * @Route("/{id}/edit", name="program_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}/delete", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}
