<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{

    /**
     * @Route("/program/", name="program_index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/{id<\d+>", methods={"POST"}, name="program_id")
     */
    public function show($id): Response
    {
        return $this->render('program/show.html.twig');
    }
}
