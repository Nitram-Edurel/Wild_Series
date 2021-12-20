<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Form\CommentType;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugify;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Comment;

/**
 * @Route("/episode")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/", name="episode_index", methods={"GET"})
     */
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{slug}/comment/new", name="comment_new", methods={"GET","POST"})
     */

    public function newComment(Request $request, Episode $episode): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/newComment.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="episode_new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        Slugify $slugify,
        MailerInterface $mailer
    ): Response {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $entityManager->persist($episode);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Un nouvel épisode vient d\'être publié !')
                ->html($this->renderView('episode/newEpisodeEmail.html.twig', ['episode' => $episode]));

            $mailer->send($email);

            return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="episode_show", methods={"GET"})
     */
    public function show(Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'episode' => $episode,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="episode_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        Episode $episode,
        EntityManagerInterface $entityManager,
        Slugify $slugify
    ): Response {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $entityManager->flush();

            return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="episode_delete", methods={"POST"})
     */
    public function delete(Request $request, Episode $episode, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $episode->getId(), $request->request->get('_token'))) {
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
    }
}