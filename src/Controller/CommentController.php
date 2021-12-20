<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Form\CommentType;

/**
 * @Route("/comment")
 */

class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment")
     */
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    // /**
    //  * @Route("/{slug}/new", name="comment_new", methods={"GET","POST"})
    //  */

    // public function new(Request $request): Response
    // {
    //     $comment = new Comment();
    //     $form = $this->createForm(CommentType::class, $comment);
    //     $form->handleRequest($request);
    //     var_dump($comment);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($comment);
    //         $comment->setAuthor($this->getUser());
    //         $comment->setEpisode($this->getEpisode());
    //         $entityManager->flush();

    //         return $this->redirectToRoute('program_index');
    //     }

    //     return $this->render('comment/new.html.twig', [
    //         'comment' => $comment,
    //         'form' => $form->createView(),
    //     ]);
    // }
}