<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorController extends AbstractController
/**
 * @Route("/actor", name="actor_")
 */
{
    /**
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $actors = $this->getDoctrine()->getRepository(Actor::class)->findAll();

        return $this->render('actor/index.html.twig', [
            'actors' => $actors
        ]);
    }
    /**
     *
     * @Route("/{id<^[0-9]+$>}", name="show")
     * @return Response
     */
    public function show(Actor $actor): Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->find($actor);
    
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found . '
            );
        }
        return $this->render('actor/show.html.twig', [

            'actor' => $actor,
            'programs' => $programs,
        ]);
    }
}